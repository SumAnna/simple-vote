<?php

namespace App\Http\Requests;

use App\Rules\UniqueUserQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreVoteRequest extends FormRequest
{
    /**
     * Determines if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Gets the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'option_ids.*' => [
                'required',
                'numeric',
                new UniqueUserQuestion
            ],
        ];
    }
}
