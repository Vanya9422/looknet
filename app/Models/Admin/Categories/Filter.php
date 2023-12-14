<?php

namespace App\Models\Admin\Categories;

use Bkwld\Cloner\Cloneable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * Class Filter
 *
 * @package App\Models
 * @property int $id
 * @property array $name
 * @property string $slug
 * @property int $order
 * @property int|null $category_id
 * @property string|null $min_name
 * @property string|null $max_name
 * @property float|null $max_value
 * @property float|null $min_value
 * @property int|null $with_values
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\Categories\FilterAnswer[] $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Admin\Categories\Category|null $category
 * @property mixed $type
 * @method static \Illuminate\Database\Eloquent\Builder|Filter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Filter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Filter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Filter whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Filter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Filter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Filter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Filter whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Filter whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Filter whereUpdatedAt($value)
 * @method static \Database\Factories\Admin\Categories\FilterFactory factory(...$parameters)
 * @mixin \Eloquent
 */
class Filter extends Model {

    use HasFactory, HasTranslations, Sluggable, Cloneable;

    /**
     * @var string[]
     */
    public array $translatable = ['name', 'sub_filter_names', 'min_name', 'max_name'];

    /**
     * @var array|string[]
     */
    protected array $cascadeDeletes = ['answers'];

    /**
     * @var array|string[]
     */
    protected array $cloneable_relations = ['answersWithoutSubFilters'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer',
        'with_values' => 'boolean'
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
        'id',
        'name',
        'with_values',
        'slug',
        'type',
        'sub_filter_names',
        'order',
        'category_id',
        'answer_id',
        'min_name',
        'max_name',
        'max_value',
        'min_value',
    ];

    /**
     * @return \string[][]
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string {
        return 'slug';
    }

    /**
     * @return HasMany
     */
    public function answers(): HasMany {
        return $this->hasMany(FilterAnswer::class)->noValues()->with('sub_filters');
    }

    /**
     * @return HasMany
     */
    public function answersWithoutSubFilters(): HasMany {
        return $this->hasMany(FilterAnswer::class)->noValues();
    }

    /**
     * @return HasMany
     */
    public function answersWithSubFilters(): HasMany {
        return $this->hasMany(FilterAnswer::class)->noValues()->with('filters');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
