<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends BaseEmployeeRequest
{
    public function additionalRules() :array
    {
        return [
            'email' => 'required|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ];
    }
}
