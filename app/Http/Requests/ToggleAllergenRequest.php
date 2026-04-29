<?php

namespace App\Http\Requests;

use App\Models\ProductDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleAllergenRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user !== null && method_exists($user, 'isManager') && $user->isManager();
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|string|exists:products,id',
            'alergen_id' => ['required', 'string', Rule::in(ProductDetail::ALLERGEN_KEYS)],
        ];
    }
}
