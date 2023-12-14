<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property string $stripe_id
 * @property int $status
 * @property string $expired_period_gep_up
 * @property string $plan_type
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePlanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUserId($value)
 * @property-read Model|\Eloquent $auto_renewal
 * @property-read Model|\Eloquent $planable
 * @property-read Model|\Eloquent $ownerable
 * @property int $plan_id
 * @property string $owner_type
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereAutoRenewal($value)
 * @mixin \Eloquent
 */
class Subscription extends Model
{
    use HasFactory;

    protected $casts = [
        'payload' => 'array'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'stripe_id',
        'status',
        'auto_renewal',
        'payload',
        'owner_id',
        'owner_type',
        'plan_id',
        'plan_type',
        'expired_period_gep_up',
        'expired_vip_days',
        'expired_top_days',
        'cancelled_at'
    ];

    /**
     * @return MorphTo
     */
    public function planable(): MorphTo
    {
        return $this->morphTo('planable', 'plan_type', 'plan_id');
    }

    /**
     * @return MorphTo
     */
    public function ownerable(): MorphTo
    {
        return $this->morphTo('ownerable', 'owner_type', 'owner_id');
    }
}
