<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use LevelUp\Experience\Concerns\GiveExperience;




class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use GiveExperience;

    protected $appends = ['rating'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'image' => 'string',
        'rating' => 'float',
        'city_id' => 'string',
        'location' => Point::class,
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::startsWith($value, "http") ? $value : ( $value == null ? "https://ui-avatars.com/api/?name=Axy+Boe" : url($value)),
        );
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socails(): HasMany
    {
        return $this->hasMany(SocailAccount::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function commissionShop(): HasOne
    {
        return $this->hasOne(CommissionShop::class)->latest();
    }
    
    

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function getRatingAttribute()
    {
        return number_format($this->reviews()->avg('rating') ?? 0, 1);
    }

    
    public function favoriteShops()
    {
        return $this->belongsToMany(CommissionShop::class, 'favorite_shops');
    }

    public function reportedShops()
    {
        return $this->belongsToMany(CommissionShop::class, 'reported_shops')->withPivot('reason');
    }
}
