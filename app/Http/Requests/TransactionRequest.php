<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'in:income,expense'
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:100000000'
            ],
            'description' => [
                'required',
                'string',
                'min:5',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Jenis transaksi harus dipilih',
            'type.in' => 'Jenis transaksi tidak valid',
            'amount.required' => 'Nominal harus diisi',
            'amount.numeric' => 'Nominal harus berupa angka',
            'amount.min' => 'Nominal minimal Rp 1',
            'amount.max' => 'Nominal maksimal Rp 100.000.000',
            'description.required' => 'Deskripsi harus diisi',
            'description.min' => 'Deskripsi minimal 5 karakter',
            'description.max' => 'Deskripsi maksimal 255 karakter'
        ];
    }
}