<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return true; // デフォルトは false。true にしないと403になる
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
          $product = $this->route('product'); // 登録時は null、更新時は対象の商品

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        'name'        => ['required', 'string', 'max:255'],
        'sku'         => ['required', 'string', 'max:50', 'unique:products,sku'],
        'price'       => ['required', 'integer', 'min:0'],
        'stock_quantity' => ['required', 'integer', 'min:0'],
        'image'       => ['nullable', 'image', 'max:2048'],
        'sku' => [
            'required', 'string', 'max:50',
            Rule::unique('products', 'sku')->ignore($product?->id), //「null ならそのまま null」という書き方です。登録時はエラーにならず、何も除外されません。
            ],
            'price'       => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ];

        // 在庫数は登録時だけ入力
        if ($this->isMethod('post')) {
            $rules['stock_quantity'] = ['required', 'integer', 'min:0'];
        }

        return $rules;
    }
}
