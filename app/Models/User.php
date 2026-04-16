<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone_number',
        'gender',
        'birth_date',
        'profile_photo_path',
        'is_phone_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_phone_verified' => 'boolean',
        ];
    }

    /**
     * User memiliki satu Vendor (jika role = vendor)
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * User memiliki banyak Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * User memiliki banyak Addresses
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * User memiliki dokumen verifikasi (KTP/SIM).
     */
    public function userDocuments()
    {
        return $this->hasMany(UserDocument::class);
    }

    /**
     * User memiliki banyak item wishlist.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
