<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use App\Models\UserMysql;
use App\Rules\TelephoneRule;
use App\Rules\CustumPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update',[UserMysql::class, $this->route('fonction')]);
        return true;
    }

    public function rules()
    {
        return [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'adresse' => 'sometimes|required|string',
            'email' => ['sometimes',new EmailRule(),'unique:users,email'],
            'password' =>['sometimes', new CustumPasswordRule()],
            'telephone' => ['sometimes',new TelephoneRule(),'unique:users,telephone'],
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