<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user !== null && method_exists($user, 'isManager') && $user->isManager();
    }

    public function rules(): array
    {
        return [
            'code'        => 'required|string|max:255',
            'taxcat'      => 'required|string|max:255',
            'reference'   => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'pricebuy'    => 'required',
            'pricesell'   => 'required',
            'name'        => 'required|string|max:255',
            'printto'     => 'nullable|string|max:255',
            'stockunits'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'lang1'       => 'nullable|string|max:255',
            'lang2'       => 'nullable|string|max:255',
            'lang3'       => 'nullable|string|max:255',
        ];
    }

    /**
     * Whitelist of fields that may be assigned directly to the Product model.
     * Excludes pricesell/printto/stockunits because the controller normalises
     * them before assignment.
     */
    public function productAttributes(): array
    {
        return $this->safe()->only([
            'name', 'code', 'reference', 'category', 'taxcat', 'pricebuy',
        ]);
    }

    /**
     * Whitelist of fields that go to the related ProductDetail row.
     */
    public function productDetailAttributes(): array
    {
        return $this->safe()->only([
            'description', 'lang1', 'lang2', 'lang3',
        ]);
    }
}
