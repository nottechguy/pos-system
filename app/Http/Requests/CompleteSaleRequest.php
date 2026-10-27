<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteSaleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'terminal_id' => ['required', 'integer', 'exists:pos_terminals,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            
            // Items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            
            // Payments
            'payments' => ['required', 'array', 'min:1'],
            'payments.*.payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'payments.*.amount' => ['required', 'numeric', 'min:0.01'],
            'payments.*.received_amount' => ['nullable', 'numeric', 'min:0.01'],
            'payments.*.reference' => ['nullable', 'string', 'max:160'],
        ];
    }
}