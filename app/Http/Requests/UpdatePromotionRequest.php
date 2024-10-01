<?php

namespace App\Http\Requests;

use App\Enums\EtatPromotion;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePromotionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'sometimes|required|unique:promotions,libelle,' . $this->route('promotion') . '|max:255',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'sometimes|required_without:duree|date|after:date_debut',
            'duree' => 'sometimes|required_without:date_fin|integer|min:1',
            'photo_couverture' => 'nullable|image|max:2048',
            'etat' => 'sometimes|required|in:' . implode(',', array_column(EtatPromotion::cases(), 'value')),
        ];
    }

    public function messages()
    {
        return [
            'libelle.sometimes.required' => 'Le champ libellé est requis.',
            'libelle.unique' => 'Ce libellé existe déjà.',
            'libelle.max' => 'Le libellé ne doit pas dépasser 255 caractères.',
            'date_debut.sometimes.required' => 'La date de début est requise.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.sometimes.required_without' => 'La date de fin ou la durée est requise.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'duree.sometimes.required_without' => 'La durée ou la date de fin est requise.',
            'duree.integer' => 'La durée doit être un nombre entier.',
            'duree.min' => 'La durée doit être au moins de 1.',
            'photo_couverture.image' => 'La couverture doit être une image valide.',
            'photo_couverture.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
            'etat.sometimes.required' => 'L\'état est requis.',
            'etat.in' => 'L\'état doit être l\'un des suivants : ' . implode(', ', array_column(EtatPromotion::cases(), 'value')),
        ];
    }
}
