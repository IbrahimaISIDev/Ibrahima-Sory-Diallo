<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|unique:promotions,libelle|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required_without:duree|date|after:date_debut',
            'duree' => 'required_without:date_fin|integer|min:1',
            'photo_couverture' => 'nullable|image|max:2048',
            'referentiels' => 'array',
            'referentiels.*' => 'exists:referentiels,id',
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => 'Le champ libellé est requis.',
            'libelle.unique' => 'Ce libellé existe déjà.',
            'libelle.max' => 'Le libellé ne doit pas dépasser 255 caractères.',
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.required_without' => 'La date de fin ou la durée est requise.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'duree.required_without' => 'La durée ou la date de fin est requise.',
            'duree.integer' => 'La durée doit être un nombre entier.',
            'duree.min' => 'La durée doit être au moins de 1.',
            'photo_couverture.image' => 'La couverture doit être une image valide.',
            'photo_couverture.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
            'referentiels.array' => 'Les référentiels doivent être un tableau.',
            'referentiels.*.exists' => 'Un ou plusieurs référentiels sélectionnés n\'existent pas.',
        ];
    }
}
