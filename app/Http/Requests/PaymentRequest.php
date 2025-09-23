<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:1000',
                'max:1000000'
            ],
            'description' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Nominal pembayaran harus diisi',
            'amount.numeric' => 'Nominal harus berupa angka',
            'amount.min' => 'Nominal minimum adalah Rp 2.000',
            'amount.max' => 'Nominal maksimum adalah Rp 1.000.000',
            'description.max' => 'Keterangan maksimal 255 karakter'
        ];
    }
}