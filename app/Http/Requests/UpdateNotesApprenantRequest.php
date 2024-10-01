<?php

// app/Http/Requests/UpdateNotesApprenantRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotesApprenantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'notes' => 'required|array',
            'notes.*.noteId' => 'required|integer|exists:notes,id',
            'notes.*.note' => 'required|numeric|min:0|max:20',
        ];
    }
}