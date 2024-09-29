<?php

// app/Http/Requests/AddNotesModuleRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNotesModuleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'notes' => 'required|array',
            'notes.*.apprenantId' => 'required|integer|exists:apprenants,id',
            'notes.*.note' => 'required|numeric|min:0|max:20',
        ];
    }
}