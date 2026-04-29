<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user !== null && method_exists($user, 'isManager') && $user->isManager();
    }

    public function rules(): array
    {
        return [
            'code'      => 'required|string|max:255',
            'reference' => 'required|string|max:255',
            'category'  => 'required|string|max:255',
            'pricebuy'  => 'required',
            'pricesell' => 'required',
            'name'      => 'required|string|max:255',
            'taxcat'    => 'nullable|string|max:255',
            'printto'   => 'nullable|string|max:255',
        ];
    }

    /**
     * Whitelist of fields that may be assigned directly to the Product model.
     */
    public function productAttributes(): array
    {
        return $this->safe()->only([
            'name', 'pricebuy', 'pricesell', 'code', 'reference', 'taxcat', 'category', 'printto',
        ]);
    }
}
