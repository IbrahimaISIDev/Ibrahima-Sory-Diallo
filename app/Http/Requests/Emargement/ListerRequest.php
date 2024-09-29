<?php

namespace App\Http\Requests\Emargement;

use Illuminate\Foundation\Http\FormRequest;

class ListerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'promotion_id' => 'required|exists:promotions,id',
            'mois' => 'nullable|integer|between:1,12',
            'date' => 'nullable|date',
            'referentiel' => 'nullable|exists:referentiels,id',
        ];
    }
}