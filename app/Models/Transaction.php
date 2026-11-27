<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public const TYPE_INCOME = 'pemasukan';

    public const TYPE_EXPENSE = 'pengeluaran';

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'type',
        'transaction_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isIncome(): bool
    {
        return $this->type === self::TYPE_INCOME;
    }

    /**
     * Membatasi transaksi pada rentang tanggal . Menggunakan whereDate
     * agar hasil konsisten di SQLite maupun MySQL.
     */
    public function scopeBetween(Builder $query, ?string $start, ?string $end): Builder
    {
        if ($start) {
            $query->whereDate('transaction_date', '>=', $start);
        }
        if ($end) {
            $query->whereDate('transaction_date', '<=', $end);
        }

        return $query;
    }

    public function scopeOfUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
