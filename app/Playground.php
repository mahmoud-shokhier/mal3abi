<?php

namespace App;

use App\Helpers\Utilities;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * App\Playground
 *
 * @property int                                $id
 * @property int                                $playground_id
 * @property float|null                         $lat
 * @property float|null                         $long
 * @property string|null                        $price_day
 * @property string|null                        $price_night
 * @property string                             $status
 * @property string|null                        $day_time
 * @property string|null                        $night_time
 * @property mixed|null                         $created_at
 * @property mixed|null                         $updated_at
 * @property-read Collection|PlaygroundImage[]  $images
 * @property-read int|null                      $images_count
 * @property-read Collection|Rate[]             $rate
 * @property-read int|null                      $rate_count
 * @method static Builder|Playground newModelQuery()
 * @method static Builder|Playground newQuery()
 * @method static Builder|Playground query()
 * @method static Builder|Playground whereCreatedAt($value)
 * @method static Builder|Playground whereDayTime($value)
 * @method static Builder|Playground whereId($value)
 * @method static Builder|Playground whereLat($value)
 * @method static Builder|Playground whereLong($value)
 * @method static Builder|Playground whereNightTime($value)
 * @method static Builder|Playground wherePlaygroundId($value)
 * @method static Builder|Playground wherePriceDay($value)
 * @method static Builder|Playground wherePriceNight($value)
 * @method static Builder|Playground whereStatus($value)
 * @method static Builder|Playground whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int                                $user_id
 * @property-read Collection|\App\Rate[]        $rates
 * @property-read int|null                      $rates_count
 * @method static Builder|Playground name($name)
 * @method static Builder|Playground phone($phone)
 * @method static Builder|Playground whereUserId($value)
 * @method static Builder|Playground distance($lat, $long)
 * @method static Builder|Playground withRate()
 * @property string                             $name
 * @property string                             $address
 * @method static Builder|Playground whereAddress($value)
 * @method static Builder|Playground whereName($value)
 * @property-read Collection|\App\Reservation[] $reservations
 * @property-read int|null                      $reservations_count
 * @method static Builder|Playground opened()
 */
class Playground extends Model
{
    #region Attributes
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'lat', 'long', 'price_day', 'price_night', 'day_time', 'night_time', 'status'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', 'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';
    #endregion Attributes

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSE;
    }

    #region Setters

    /**
     * @param $value
     *
     * @throws \Exception
     */
    public function setImages($value)
    {
        foreach ($this->images as $image){
            $image->deleteImage();
        };
        foreach ($value as $image) {
            $path = $image->store('public');
            $this->images()->create(['image' => $path]);
        }
    }
    #endregion Setters

    #region Relations

    /**
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(PlaygroundImage::class);
    }

    /**
     * @return HasMany
     */
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    /**
     * @return HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    #endregion Relations

    #region scopes
    /**
     * @param Builder     $query
     * @param String|null $name
     *
     * @return Builder|void
     */
    public function scopeName(Builder $query, ?string $name): Builder
    {
        if ($name)
            $query->where('name', 'LIKE', '%' . $name . '%');
        return $query;
    }

    /**
     * @param Builder     $query
     * @param String|null $phone
     *
     * @return Builder|void
     */
    public function scopePhone(Builder $query, ?string $phone): Builder
    {
        if ($phone)
            $query->where('phone', 'LIKE', '%' . $phone . '%');
        return $query;
    }

    /**
     * @param Builder     $query
     * @param String|null $lat
     * @param String|null $long
     *
     * @return Builder|void
     */
    public function scopeDistance(Builder $query, ?string $lat, ?string $long): Builder
    {
        if ($lat && $long) {
            $distanceQuery = Utilities::distanceQuery($lat, $long, 'playgrounds');
            $query->selectRaw("{$distanceQuery}");
            $query->orderBy("distance", "ASC");
        }
        return $query;
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeWithRate(Builder $builder)
    {
        return $builder->withCount(['rates as rate' => function (Builder $builder) {
            $builder
                ->select([])
                ->selectRaw('IFNULL(SUM(rates.rate) / COUNT(*), 1)');
        }]);
    }

    /**
     * @param Builder|Playground $builder
     *
     * @return mixed
     */
    public function scopeOpened(Builder $builder)
    {
        return $builder->whereStatus(Playground::STATUS_OPEN);
    }
    #endregion Relations
}
