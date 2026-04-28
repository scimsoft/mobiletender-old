<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveAddOnProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user !== null && method_exists($user, 'isManager') && $user->isManager();
    }

    public function rules(): array
    {
        return [
            'product_id'      => 'required|string|exists:products,id',
            'adon_product_id' => 'required|string|exists:products,id',
        ];
    }
}
