<?php

namespace App\Models\Admin\Categories;

use App\Traits\OrderGlobalScopeAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * Class FilterAnswer
 *
 * @package App\Models
 * @property int $id
 * @property array $name
 * @property int $order
 * @property int|null $filter_id
 * @property int|null $has_sub_filters
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin\Categories\Filter|null $filter
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FilterAnswer whereUpdatedAt($value)
 * @method static \Database\Factories\Admin\Categories\FilterAnswerFactory factory(...$parameters)
 * @mixin \Eloquent
 */
class FilterAnswer extends Model
{
    use HasFactory, HasTranslations, OrderGlobalScopeAble;

    /**
     * @var string[]
     */
    public array $translatable = ['name'];

    /**
     * @var string $slugName
     */
    public static string $slugName = 'name';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'number_value' => 'integer',
        'string_value' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'number_value',
        'string_value',
        'boolean_value',
        'has_sub_filters',
        'order',
        'filter_id'
    ];

    /**
     * @return BelongsTo
     */
    public function filter(): BelongsTo {
        return $this->belongsTo(Filter::class);
    }

    /**
     * @return HasMany
     */
    public function filters(): HasMany {
        return $this->hasMany(Filter::class, 'answer_id');
    }

    /**
     * @return HasMany
     */
    public function sub_filters(): HasMany {
        return $this->filters()->with('answers');
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeNoValues($query)
    {
        $query->whereNull('string_value')->whereNull('number_value');
    }
}
