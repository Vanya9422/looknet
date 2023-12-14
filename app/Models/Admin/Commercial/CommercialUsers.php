<?php

namespace App\Models\Admin\Commercial;

use App\Models\Group;
use App\Traits\MediaConversionAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use function config;

/**
 * Class CommercialUsers
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property int $status
 * @property string $group
 * @property int $gep_up
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Media|null $avatar
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereGepUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers wherePeriodOfStay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereUpdatedAt($value)
 * @property int $order
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereOrder($value)
 * @property int $vip_days
 * @property int $top_days
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereCountDays($value)
 * @property int $group_id
 * @property-read \App\Models\Group|null $period
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers wherePeriodOfStayId($value)
 * @property int|null $period_days
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers wherePeriodDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommercialUsers whereVipTime($value)
 * @mixin \Eloquent
 */
class CommercialUsers extends Model implements HasMedia {

    use HasFactory, InteractsWithMedia, HasTranslations, MediaConversionAble {
        MediaConversionAble::registerMediaConversions insteadof InteractsWithMedia;
    }

    /**
     * @var string[]
     */
    public array $translatable = ['name', 'description'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'group_id',
        'vip_days',
        'top_days',
        'gep_up',
        'period_days',
        'order',
        'status',
    ];

    /**
     * @return MorphOne
     */
    public function avatar(): MorphOne {
        return $this
            ->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', '=', \App\Enums\MediaCollections::COMMERCIAL_USER_AVATAR);
    }

    /**
     * @param $value
     */
    public function setDescriptionAttribute($value) {
        $this->attributes['description'] = strip_tags($value);
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo {
        return $this->belongsTo(Group::class);
    }
}
