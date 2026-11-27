<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;
        $type = $this->input('type');

        return [
            'type' => ['required', Rule::in(['pemasukan', 'pengeluaran'])],
            // Kategori harus milik pengguna dan sesuai jenis transaksi.
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(
                    fn ($query) => $query->where('user_id', $userId)->where('type', $type)
                ),
            ],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'jenis transaksi',
            'category_id' => 'kategori',
            'amount' => 'nominal',
            'transaction_date' => 'tanggal',
            'description' => 'keterangan',
        ];
    }
}
