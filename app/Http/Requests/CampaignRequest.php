<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
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
            'group_id' => ['required', 'string', Rule::exists('groups', 'id')],
            'name' => ['required','string', 'max:255'],
            'description' => ['required','string', 'max:255'],
            'status' => [
                'required',
                'string',
                'in:active,paused,expired,cancelled'
            ],
            'start_date' => ['required','date', 'date_format:Y-m-d'],
            'end_date' => ['nullable','date', 'after_or_equal:start_date', 'date_format:Y-m-d'],
        ];
    }
}
