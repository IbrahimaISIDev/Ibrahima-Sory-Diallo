<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferentielRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|unique:referentiels,libelle|max:255',
            'code' => 'required|unique:referentiels,code|max:255',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => 'Le champ libellé est requis.',
            'libelle.unique' => 'Ce libellé existe déjà.',
            'libelle.max' => 'Le libellé ne doit pas dépasser 255 caractères.',
            'code.required' => 'Le champ code est requis.',
            'code.unique' => 'Ce code existe déjà.',
            'code.max' => 'Le code ne doit pas dépasser 255 caractères.',
            'photo.image' => 'La photo doit être une image valide.',
            'photo.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}
