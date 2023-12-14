<?php

namespace App\Models;

use App\Enums\MediaCollections;
use App\Models\Admin\Support\Complaint;
use App\Models\Products\Advertise;
use App\Traits\MediaConversionAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property string $comment
 * @property int $star
 * @property int $status Информация о status детально описано в файле ReviewStatusEnum
 * @property int|null $user_id
 * @property int|null $author_id
 * @property int $advertise_id
 * @property int $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $pictures
 * @property-read int|null $pictures_count
 * @property-read \App\Models\User|null $user
 * @property Advertise $advertise
 * @property mixed $author
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereAdvertiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @mixin \Eloquent
 */
class Review extends Model implements HasMedia {

    use HasFactory, InteractsWithMedia, MediaConversionAble {
        MediaConversionAble::registerMediaConversions insteadof InteractsWithMedia;
    }

    /**
     * @var string[]
     */
    protected $fillable = [
        'comment',
        'star',
        'status',
        'has_image',
        'published',
        'published_at',
        'advertise_id',
        'user_id',
        'author_id',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo {
        return $this->belongsTo(User::class,'author_id');
    }

    /**
     * @return BelongsTo
     */
    public function advertise(): BelongsTo {
        return $this->belongsTo(Advertise::class);
    }

    /**
     * @return MorphMany
     */
    public function pictures(): MorphMany {
        return $this->media()->where('collection_name', '=', MediaCollections::REVIEW_PICTURES);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function complaint(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Complaint::class,'complaintable');
    }
}
