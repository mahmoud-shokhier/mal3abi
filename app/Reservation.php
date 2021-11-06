<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Reservation
 *
 * @property int            $id
 * @property int            $playground_id
 * @property int|null       $user_id
 * @property string         $day
 * @property string         $start
 * @property string         $end
 * @property string         $status
 * @property string|null    $notes
 * @property mixed|null     $created_at
 * @property mixed|null     $updated_at
 * @property-read User      $playground
 * @property-read User|null $user
 * @method static Builder|Reservation newModelQuery()
 * @method static Builder|Reservation newQuery()
 * @method static Builder|Reservation query()
 * @method static Builder|Reservation whereCreatedAt($value)
 * @method static Builder|Reservation whereDay($value)
 * @method static Builder|Reservation whereEnd($value)
 * @method static Builder|Reservation whereId($value)
 * @method static Builder|Reservation whereNotes($value)
 * @method static Builder|Reservation wherePlaygroundId($value)
 * @method static Builder|Reservation whereStart($value)
 * @method static Builder|Reservation whereStatus($value)
 * @method static Builder|Reservation whereUpdatedAt($value)
 * @method static Builder|Reservation whereUserId($value)
 * @mixin Eloquent
 * @method static Builder|Reservation day($day)
 * @method static Builder|Reservation status($status)
 */
class Reservation extends Model
{
    #region Attributes
    protected $fillable = ['user_id', 'day', 'start', 'end', 'status', 'notes'];

    protected $dates = ['start_at', 'end_date'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', 'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    const STATUS_PENDING = 'pending';
    const STATUS_DONE = 'done';
    #endregion Attributes

    #region Relations

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function playground()
    {
        return $this->belongsTo(Playground::class);
    }

    #endregion Relations
    #region Scopes
    /**
     * @param Builder|Reservation $query
     * @param                     $day
     *
     * @return Builder|void
     */
    public function scopeDay(Builder $query, $day)
    {
        if ($day)
            return $query->whereDay($day);
    }

    /**
     * @param Builder|Reservation $query
     * @param                     $status
     *
     * @return Builder|void
     */
    public function scopeStatus(Builder $query, $status)
    {
        if ($status)
            return $query->whereStatus($status);
    }
    #endregion Scopes
}
