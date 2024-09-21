<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules()
    {
        return [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'adresse' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $this->route('user')->id,
            'password' => 'sometimes|required|string|min:8',
            'telephone' => 'sometimes|required|string',
            'photo' => 'nullable|image|max:2048',
            'fonction' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:actif,inactif',
            'role' => 'sometimes|required|in:Admin,Coach,Manager,CM'
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'fonction.required' => 'La fonction est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être actif ou inactif.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle doit être Admin, Coach, Manager ou CM.'
        ];
    }
}