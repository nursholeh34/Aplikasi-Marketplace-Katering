<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_MERCHANT = 'merchant';
    public const ROLE_CUSTOMER = 'customer';

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function merchant(): HasOne
    {
        return $this->hasOne(Merchant::class);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function isMerchant(): bool
    {
        return $this->role === self::ROLE_MERCHANT;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    /** Nama route dashboard sesuai peran. */
    public function homeRoute(): string
    {
        return $this->isMerchant() ? 'merchant.dashboard' : 'customer.dashboard';
    }
}
