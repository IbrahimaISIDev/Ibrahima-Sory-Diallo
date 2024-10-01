<?php

namespace App\Http\Requests\Emargement;

use Illuminate\Foundation\Http\FormRequest;

class EnregistrerApprenantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'nullable|date',
        ];
    }
}