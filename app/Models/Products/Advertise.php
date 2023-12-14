<?php

namespace App\Models\Products;

use App\Models\Admin\Categories\Category;
use App\Models\Admin\Categories\FilterAnswer;
use App\Models\Admin\Countries\City;
use App\Models\Admin\Support\Complaint;
use App\Models\Chat\Conversation;
use App\Models\Payment\Subscription;
use App\Models\User;
use App\Traits\MediaConversionAble;
use App\Traits\TimezoneChangeAble;
use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Advertise
 *
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\Categories\FilterAnswer[] $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Admin\Categories\Category|null $category
 * @property-read \App\Models\Admin\Countries\City|null $city
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Models\Media[] $gallery
 * @property-read int|null $gallery_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|Advertise newModelQuery()
 * @method static Builder|Advertise newQuery()
 * @method static Builder|Advertise query()
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float|null $price
 * @property string $latitude
 * @property string $longitude
 * @property string|null $link
 * @property int $status Не проверено 0,  Активно 1, Не активно 2, Отклонено 3, Забанено 4
 * @property string|null $contacts
 * @property int|null $available_cost
 * @property int|null $show_phone
 * @property int|null $show_details
 * @property int|null $added_favorites
 * @property int $user_id
 * @property int $category_id
 * @property int $city_id
 * @property Carbon|null $hide_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $inactively_date
 * @property int|null $auto_renewal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products\AdvertiseStatistic[] $statistics
 * @property-read int|null $statistics_count
 * @method static Builder|Advertise whereAddedFavorites($value)
 * @method static Builder|Advertise whereAutoRenewal($value)
 * @method static Builder|Advertise whereCategoryId($value)
 * @method static Builder|Advertise whereCityId($value)
 * @method static Builder|Advertise whereContactMessage($value)
 * @method static Builder|Advertise whereContactPhone($value)
 * @method static Builder|Advertise whereCreatedAt($value)
 * @method static Builder|Advertise whereDescription($value)
 * @method static Builder|Advertise whereId($value)
 * @method static Builder|Advertise whereInactivelyDate($value)
 * @method static Builder|Advertise whereLatitude($value)
 * @method static Builder|Advertise whereLink($value)
 * @method static Builder|Advertise whereLongitude($value)
 * @method static Builder|Advertise whereName($value)
 * @method static Builder|Advertise wherePrice($value)
 * @method static Builder|Advertise whereShowDetails($value)
 * @method static Builder|Advertise whereShowPhone($value)
 * @method static Builder|Advertise whereSlug($value)
 * @method static Builder|Advertise whereStatus($value)
 * @method static Builder|Advertise whereUpdatedAt($value)
 * @method static Builder|Advertise whereUserId($value)
 * @property int $id
 * @property string $address
 * @property int|null $price_policy Ценовая Политика объявление. платное 0, бесплатное 1, обмен 2, договорная 3
 * @property mixed reject_message
 * @method static Builder|Advertise whereAddress($value)
 * @method static Builder|Advertise whereAvailableCost($value)
 * @method static Builder|Advertise wherePricePolicy($value)
 * @method static Builder|Advertise whereRejectMessage($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products\AdvertiseStatistic[] $favorites
 * @property-read int|null $favorites_count
 * @property-read string $humans_time
 * @method static \Database\Factories\Products\AdvertiseFactory factory(...$parameters)
 * @property Carbon|null $deleted_at
 * @method static \Illuminate\Database\Query\Builder|Advertise onlyTrashed()
 * @method static Builder|Advertise whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Advertise withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Advertise withoutTrashed()
 * @property string|null $contact_phone
 * @property string|null $contact_message
 * @property string|null $filters
 * @property int|null $contact_phone_numeric
 * @property mixed $conversation
 * @method static Builder|Advertise whereContactPhoneNumeric($value)
 * @method static Builder|Advertise whereContacts($value)
 * @property-read bool $exists_unread_messages
 * @property mixed $fake_data
 * @property mixed $is_fake
 * @method static Builder|Advertise whereFilters($value)
 * @mixin \Eloquent
 */
class Advertise extends Model implements HasMedia {

    use HasFactory, TimezoneChangeAble,  Sluggable, InteractsWithMedia,
        SoftDeletes, CascadeSoftDeletes, MediaConversionAble {
        MediaConversionAble::registerMediaConversions insteadof InteractsWithMedia;
    }

    /**
     * @var array|string[]
     */
    protected array $cascadeDeletes = ['gallery'];

    protected $appends = ['exists_unread_messages', 'is_vip', 'is_top'];

    protected $casts = [
      'formatted_filters' => 'array',
      'fake_data' => 'array',
    ];

    /**
     * @var string $slugName
     */
    public static string $slugName = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'fake_data',
        'hide_address',
        'price',
        'address',
        'latitude',
        'longitude',
        'answer_ids',
        'published',
        'is_fake',
        'link',
        'auto_renewal',
        'status',
        'formatted_filters',
        'available_cost',
        'price_policy',
        'show_phone',
        'show_details',
        'added_favorites',
        'contacts',
        'contact_phone',
        'contact_phone',
        'contact_phone_numeric',
        'user_id',
        'category_id',
        'city_id',
        'inactively_date',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \string[][]
     */
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public static array $selectedFields = [
        'advertises.id', 'advertises.name', 'advertises.user_id', 'advertises.city_id',
        'advertises.longitude', 'advertises.latitude', 'advertises.created_at',
        'advertises.category_id', 'advertises.contact_phone', 'advertises.price_policy', 'advertises.price',
        'advertises.status', 'advertises.slug','advertises.published', 'advertises.updated_at'
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string {
        return 'slug';
    }

    /**
     * @return Attribute
     */
    protected function isVip(): Attribute
    {
        return new Attribute(
            get: fn () => $this->subscriptions()
                ->where('expired_vip_days', '>=', Carbon::now())->exists()
        );
    }

    /**
     * @return Attribute
     */
    protected function isUp(): Attribute
    {
        return new Attribute(
            get: fn () => $this->subscriptions()
                ->where('expired_period_gep_up', '>=', Carbon::now())->exists()
        );
    }

    /**
     * @return Attribute
     */
    protected function isTop(): Attribute
    {
        return new Attribute(
            get: fn () => $this->subscriptions()
                ->where('expired_top_days', '>=', Carbon::now())
                ->exists()
        );
    }

    /**
     * @param $query
     */
    public function noFakeScope($query)
    {
        return $query->where('is_fake', '=', false);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return MorphMany
     */
    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Subscription::class,'owner');
    }

    /**
     * @return MorphOne
     */
    public function subscription(): MorphOne {
        return $this->morphOne(Subscription::class,'owner');
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo {
        return $this->belongsTo(City::class);
    }

    /**
     * @return HasOne
     */
    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class, 'advertise_id');
    }

    /**
     * @return bool
     */
    public function getExistsUnreadMessagesAttribute(): bool
    {
        if (!user()) return false;

        $this?->conversation?->loadCount(['unread_messages' => function($q) {
            $q->join('chat_participation as part', function ($q) {
                $q->on('part.id', '=', 'chat_message_notifications.participation_id')
                    ->where('part.messageable_id', '=', user()->getKey());
            });
        }]);

        return $this?->conversation?->unread_messages_count > 0;
    }

    /**
     * @return BelongsToMany
     */
    public function answers(): BelongsToMany {
        return $this->belongsToMany(FilterAnswer::class,'advertise_answers');
    }

    /**
     * @return MorphMany
     */
    public function gallery(): MorphMany {
        return $this->media()
            ->where('collection_name', '=', \App\Enums\MediaCollections::ADVERTISE_COLLECTION)
            ->orderBy('order_column', 'ASC');
    }

    /**
     * @return MorphOne
     */
    public function previewImage(): MorphOne {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->orderBy('order_column', 'ASC')
            ->where('collection_name', '=', \App\Enums\MediaCollections::ADVERTISE_COLLECTION);
    }

    /**
     * @return MorphOne
     */
    public function fake_user_avatar(): MorphOne {
        return $this
            ->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', '=', \App\Enums\MediaCollections::USER_FAKE_AVATAR_ADVERTISE);
    }

    /**
     * @return HasMany
     */
    public function statistics(): HasMany {
        return $this->hasMany(AdvertiseStatistic::class);
    }

    /**
     * @return HasMany
     */
    public function favorites(): HasMany {
        return $this->statistics()->where('type', '=', 2);
    }

    /**
     * // TODO avelacnel traiti mech
     * @return string
     */
    public function getHumansTimeAttribute(): string {
        $date = $this->created_at;
        $now = $date->now();

        return $date->diffForHumans($now, true);
    }

    /**
     * @param array $value
     * @return string
     */
    public function setAnswerIdsAttribute(array $value): string {
        $answerIds = '';
        foreach ($value as $answer) $answerIds .= "[$answer]";
        return $this->attributes['answer_ids'] = $answerIds;
    }

    /**
     * @param $value
     */
    public function setDescriptionAttribute($value) {
        $this->attributes['description'] = strip_tags($value);
    }

    /**
     * @return MorphOne
     */
    public function complaint(): MorphOne
    {
        return $this->morphOne(Complaint::class,'complaintable');
    }
}
