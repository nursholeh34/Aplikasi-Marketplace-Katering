<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $fillable = ['merchant_id', 'name', 'description', 'photo', 'price', 'is_available'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /** URL foto menu (null bila belum ada). Butuh `php artisan storage:link`. */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/'.$this->photo) : null;
    }
}
