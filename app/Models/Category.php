<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public const TYPE_INCOME = 'pemasukan';

    public const TYPE_EXPENSE = 'pengeluaran';

    /** Kategori pengeluaran yang dipakai otomatis saat setoran tabungan. */
    public const SAVINGS_NAME = 'Tabungan';

    public const DEFAULTS = [
        self::TYPE_INCOME => ['Gaji', 'Bonus', 'Hadiah', 'Lainnya'],
        self::TYPE_EXPENSE => ['Makanan', 'Transportasi', 'Belanja', 'Tagihan', 'Hiburan', 'Kesehatan', 'Pendidikan', self::SAVINGS_NAME, 'Lainnya'],
    ];

    protected $fillable = ['user_id', 'name', 'type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Membuat kategori bawaan untuk akun pengguna baru.
     */
    public static function createDefaultsFor(User $user): void
    {
        foreach (self::DEFAULTS as $type => $names) {
            foreach ($names as $name) {
                static::firstOrCreate([
                    'user_id' => $user->id,
                    'name' => $name,
                    'type' => $type,
                ]);
            }
        }
    }
}
