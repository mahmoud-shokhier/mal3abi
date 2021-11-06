<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Rate
 *
 * @property int         $id
 * @property int         $user_id
 * @property int         $playground_id
 * @property int         $rate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Rate newModelQuery()
 * @method static Builder|Rate newQuery()
 * @method static Builder|Rate query()
 * @method static Builder|Rate whereCreatedAt($value)
 * @method static Builder|Rate whereId($value)
 * @method static Builder|Rate wherePlaygroundId($value)
 * @method static Builder|Rate whereRate($value)
 * @method static Builder|Rate whereUpdatedAt($value)
 * @method static Builder|Rate whereUserId($value)
 * @mixin Eloquent
 */
class Rate extends Model
{
    protected $fillable = ['user_id', 'playground_id', 'rate'];
}
