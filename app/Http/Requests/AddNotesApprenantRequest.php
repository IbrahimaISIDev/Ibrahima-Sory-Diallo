<?php

// app/Http/Requests/AddNotesApprenantRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNotesApprenantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'apprenantId' => 'required|integer|exists:apprenants,id',
            'notes' => 'required|array',
            'notes.*.moduleId' => 'required|integer|exists:modules,id',
            'notes.*.note' => 'required|numeric|min:0|max:20',
        ];
    }
}