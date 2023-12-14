<?php

namespace App\Models\Chat;

use App\Chat\ConfigurationManager;
use App\Enums\MediaCollections;
use App\Events\Chat\MessageWasSent;
use App\Models\BaseModel;
use App\Models\Media;
use App\Models\User;
use App\Notifications\V1\AdvertiseFirstMessage;
use App\Traits\MediaConversionAble;
use App\Traits\TimezoneChangeAble;
use App\Traits\UploadAble;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use function broadcast;

/**
 * Class Message
 *
 * @package App\Chat\Models
 * @property int $id
 * @property string $body
 * @property int $conversation_id
 * @property int|null $participation_id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat\Conversation $conversation
 * @property-read null $sender
 * @property-read \App\Models\Chat\Participation|null $participation
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereParticipationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Models\Media[] $files
 * @property-read int|null $files_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Query\Builder|Message onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Message withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Message withoutTrashed()
 * @property-read string $send_time
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat\MessageNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-write mixed $description
 * @mixin \Eloquent
 */
class Message extends BaseModel implements HasMedia
{
    use InteractsWithMedia, TimezoneChangeAble, UploadAble, MediaConversionAble {
        MediaConversionAble::registerMediaConversions insteadof InteractsWithMedia;
    }

    /**
     * @var string
     */
    protected $table = ConfigurationManager::MESSAGES_TABLE;

    /**
     * @var string[]
     */
    protected $fillable = [
        'body',
        'participation_id',
        'type',
        'data',
    ];

    /**
     * @var array
     */
    public static array $selectedRelationsConversation = [
        'files:id,model_id,model_type,disk,conversions_disk,collection_name,custom_properties,file_name,image_key,name',
        'participation:id,conversation_id,messageable_id,messageable_type',
        'participation.messageable:id,first_name,last_name',
        'participation.messageable.avatar:id,model_id,model_type,disk,conversions_disk,collection_name,custom_properties,file_name',
    ];

//    /**
//     * All of the relationships to be touched.
//     *
//     * @var array
//     */
//    protected $touches = ['conversation'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'flagged' => 'boolean',
        'data'    => 'array',
    ];

//    protected $appends = ['sender'];

    /**
     * @return BelongsTo
     */
    public function participation(): BelongsTo {
        return $this->belongsTo(Participation::class, 'participation_id');
    }

    /**
     * @param Model $participant
     * @return mixed
     */
    public function unreadCount(Model $participant): mixed {
        return MessageNotification::where('messageable_id', $participant->getKey())
            ->where('is_seen', 0)
            ->where('messageable_type', $participant->getMorphClass())
            ->count();
    }

    /**
     * @return BelongsTo
     */
    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * @param $value
     */
    public function setDescriptionAttribute($value) {
        $this->attributes['body'] = strip_tags($value);
    }

    /**
     * Adds a message to a conversation.
     *
     * @param Conversation $conversation
     * @param string $body
     * @param Participation $participant
     * @param User $sender
     * @param array $attachedFiles
     * @return Message
     * @throws \Throwable
     */
    public function send(
        Conversation $conversation,
        string $body,
        Participation $participant,
        User $sender,
        array $attachedFiles = []
    ): Message {
        $firstMessage = !$conversation->messages()->exists();

        \DB::transaction(function () use (
            $conversation,
            $body,
            $participant,
            $sender,
            $attachedFiles,
            &$message
        ) {
            /** @var Message $message */
            $message = $conversation->messages()->create([
                'body' => $body,
                'participation_id' => $participant->getKey(),
            ]);

            if (!empty($attachedFiles))
                foreach ($attachedFiles as $attachedFile)
                    $this->addResponsiveImageForChat(
                        $message,
                        $attachedFile,
                        MediaCollections::CHAT_FILES,
                        [],
                        Media::PUBLIC,
                        Media::CHAT_IMAGE_CONVERSIONS
                    );
        });

        $message->load('files');

        MessageNotification::make($message, $conversation);

        if (\Chat::getInstance()->broadcasts()) {
            broadcast(MessageWasSent::make($message))->toOthers();
        }

        if ($firstMessage && !$conversation->ticket_id) {
            $receiverUser = $conversation->participantReceiver($sender)->messageable;

            $receiverUser->notify((new AdvertiseFirstMessage(
                $message->body,
                app()->getLocale(),
                $conversation->advertise
            )));
        }

        $conversation->touch();

        if ($conversation->isDeleteFromStarter() || $conversation->isDeleteFromReceiver()) {
            $conversation->reset();
        }

        return $message;
    }

    /**
     * Creates an entry in the message_notification table for each participant
     * This will be used to determine if a message is read or deleted.
     *
     * @param Message $message
     */
    protected function createNotifications($message)
    {
        MessageNotification::make($message, $message->conversation);
    }

    /**
     * @return string
     */
    public function getSendTimeAttribute(): string {
        return Carbon::parse($this->created_at)->format('h:i a');
    }

    /**
     * Deletes a message for the participant.
     *
     * @param Model $participant
     *
     * @return void
     */
    public function trash(Model $participant): void
    {
        MessageNotification::where('messageable_id', $participant->getKey())
            ->where('messageable_type', $participant->getMorphClass())
            ->where('message_id', $this->getKey())
            ->delete();

//        if ($this->unDeletedCount() === 0) {
//            event(new AllParticipantsDeletedMessage($this));
//        }
    }

    /**
     * @return int
     */
    public function unDeletedCount(): int {
        return MessageNotification::where('message_id', $this->getKey())->count();
    }

    /**
     * Return user notification for specific message.
     *
     * @param Model $participant
     *
     * @return MessageNotification
     */
    public function getNotification(Model $participant): MessageNotification
    {
        return MessageNotification::where('messageable_id', $participant->getKey())
            ->where('messageable_type', $participant->getMorphClass())
            ->where('message_id', $this->id)
            ->select([
                '*',
                'updated_at as read_at',
            ])
            ->first();
    }

    /**
     * Marks message as read.
     *
     * @param $participant
     */
    public function markRead($participant): void
    {
        $this->getNotification($participant)->markAsRead();
    }

    public function flagged(Model $participant): bool
    {
        return (bool) MessageNotification::where('messageable_id', $participant->getKey())
            ->where('message_id', $this->id)
            ->where('messageable_type', $participant->getMorphClass())
            ->where('flagged', 1)
            ->first();
    }

    public function toggleFlag(Model $participant): self
    {
        MessageNotification::where('messageable_id', $participant->getKey())
            ->where('message_id', $this->id)
            ->where('messageable_type', $participant->getMorphClass())
            ->update(['flagged' => $this->flagged($participant) ? false : true]);

        return $this;
    }

    /**
     * @return MorphMany
     */
    public function files(): MorphMany {
        return $this->media()->where('collection_name', '=', MediaCollections::CHAT_FILES);
    }

    /**
     * @return HasMany
     */
    public function notifications(): HasMany {
        return $this->hasMany(MessageNotification::class);
    }
}
