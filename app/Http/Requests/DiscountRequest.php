<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'campaign_id' => ['required', 'string', Rule::exists('campaigns', 'id')],
            'name'  => ['required','string'],
            'value' => ['required', 'decimal:0,2', 'min:0'],
            'type' => [
                'required',
                'string',
                'in:amount,percentage'
            ],
        ];
    }
}
