<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'department_id' => 'required|exists:departments,id',
            'category_id' => 'nullable|exists:product_categories,id',
            'inventory_unit_id' => 'required|exists:measurement_units,id',
            'sale_unit_id' => 'required|exists:measurement_units,id',
            'sku' => 'required|string|max:50|unique:products,sku,' . $productId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_type' => 'required|string|max:50',
            'allows_fractional_qty' => 'boolean',
            'track_inventory' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}