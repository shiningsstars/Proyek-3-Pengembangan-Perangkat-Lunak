<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateActivityRequest extends StoreActivityRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['code'] = [
            'required', 'string', 'max:30',
            Rule::unique('activities', 'code')->ignore($this->route('activity')),
        ];

        return $rules;
    }
}