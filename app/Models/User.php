<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
        'birth_date',
        'gender',
        'position',
        'department',
        'education',
        'bio',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'show_in_about',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'show_in_about' => 'boolean',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPenjual()
    {
        return $this->role === 'penjual';
    }

    public function isPengguna()
    {
        return $this->role === 'pengguna';
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return asset('images/default-avatar.png');
    }

    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->name;
    }

    public function scopeTeamMembers($query)
    {
        return $query->whereIn('role', ['admin', 'penjual'])
                     ->where('show_in_about', true)
                     ->orderBy('role', 'asc')
                     ->orderBy('name', 'asc');
    }
}
