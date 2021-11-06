<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\Token;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\User
 *
 * @property int                                                        $id
 * @property string                                                     $role
 * @property string                                                     $name
 * @property string                                                     $email
 * @property string                                                     $password
 * @property string                                                     $phone
 * @property string|null                                                $national_number
 * @property string|null                                                $bank_account
 * @property string                                                     $address
 * @property string|null                                                $avatar
 * @property string|null                                                $remember_token
 * @property mixed|null                                                 $created_at
 * @property mixed|null                                                 $updated_at
 * @property-read Collection|Client[]                                   $clients
 * @property-read int|null                                              $clients_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null                                              $notifications_count
 * @property-read Collection|PlaygroundImage[]                          $playgroundImages
 * @property-read int|null                                              $playground_images_count
 * @property-read Playground|null                                       $playgroundInfo
 * @property-read Collection|Reservation[]                              $playgroundReservation
 * @property-read int|null                                              $playground_reservation_count
 * @property-read Collection|Reservation[]                              $reservations
 * @property-read int|null                                              $reservations_count
 * @property-read Collection|Token[]                                    $tokens
 * @property-read int|null                                              $tokens_count
 * @method static Builder|User address($address)
 * @method static Builder|User email($email)
 * @method static Builder|User name($name)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User phone($phone)
 * @method static Builder|User query()
 * @method static Builder|User role($role)
 * @method static Builder|User whereAddress($value)
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereBankAccount($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereNationalNumber($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|\App\Reservation[]                         $playgroundReservations
 * @property-read int|null                                              $playground_reservations_count
 * @property-read Collection|\App\Playground[]                          $playgrounds
 * @property-read int|null                                              $playgrounds_count
 * @property string|null                                                $reset_code
 * @method static Builder|User whereResetCode($value)
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;

    #region Attributes
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'name', 'email', 'password', 'reset_code', 'phone', 'national_number', 'bank_account', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime', 'created_at' => 'datetime:Y-m-d H:i:s', 'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    const Admin = 'admin';
    const Playground = 'playground';
    const User = 'user';
    const All = [self::Admin, self::Playground, self::User];
    #endregion Attributes

    #region Setters

    /**
     * Hash user password, if not already hashed
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
        if (Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * @param $value
     */
    public function setAvatarAttribute($value)
    {
        if ($value) {
            $this->attributes['avatar'] = $value->store('public');
            /** @var User $user */
            if ($user = auth()->user()) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
        }
    }
    #endregion Setters

    #region Relations

    /**
     * @return HasMany
     */
    public function playgrounds()
    {
        return $this->hasMany(Playground::class)->withRate();
    }

    /**
     * @return HasMany|Reservation
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * @return HasMany
     */
    public function playgroundReservations()
    {
        return $this->hasMany(Reservation::class);
    }
    #endregion Relations


    #region Scopes

    /**
     * @param Builder $query
     * @param null|string
     *
     * @return Builder|void
     */
    public function scopeName(Builder $query, ?string $name): Builder
    {
        if ($name)
            return $query->where('name', 'LIKE', '%' . $name . '%');
    }

    /**
     * Scope a query to only add where email to query if email isn't null
     *
     * @param Builder $query
     * @param null|string
     *
     * @return Builder|void
     */
    public function scopeEmail(Builder $query, ?string $email): Builder
    {
        if ($email)
            return $query->where('email', 'LIKE', '%' . $email . '%');
    }

    /**
     * Scope a query to only add where phone to query if phone isn't null
     *
     * @param Builder $query
     * @param null|string
     *
     * @return Builder|void
     */
    public function scopePhone(Builder $query, ?string $phone): Builder
    {
        if ($phone)
            return $query->where('phone', 'LIKE', '%' . $phone . '%');
    }

    /**
     * Scope a query to only add where address to query if address isn't null
     *
     * @param Builder $query
     * @param null|string
     *
     * @return Builder|void
     */
    public function scopeAddress(Builder $query, ?string $address): Builder
    {
        return $query->where('address', 'LIKE', '%' . $address . '%');
    }

    /**
     * Scope a query to only add where role to query if role isn't null
     *
     * @param Builder|User $query
     * @param null|string
     *
     * @return Builder|void
     */
    public function scopeRole(Builder $query, ?string $role): Builder
    {
        if ($role)
            return $query->whereRole($role);
    }
    #endregion SCopes


    #region JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    #endregion JWT
}
