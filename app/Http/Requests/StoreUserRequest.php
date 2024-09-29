<?php

namespace App\Http\Requests;

use App\Enums\EtatEnum;
use App\Models\UserMysql;
use App\Rules\EmailRule;
use App\Enums\FonctionState;
use App\Rules\TelephoneRule;
use App\Rules\CustumPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', [UserMysql::class, $this->input('fonction')]);
        return true;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'role_id' => 'required|numeric|exists:roles,id|in:1,2',
            'email' => ['required',new EmailRule(),'unique:users,email'],
            'password' =>['required', new CustumPasswordRule()],
            'telephone' => ['required',new TelephoneRule(),'unique:users,telephone'],
            'photo' => 'nullable|image|max:2048',
            'statut' => ['string', 'in:' . implode(',', array_map(fn($case) => $case->value, EtatEnum::cases()))],
            'fonction' => 'required|string|max:255',
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
            'status.in' => 'Le statut doit être actif.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle doit être Admin, Coach, Manager ou CM.'
        ];
    }
}