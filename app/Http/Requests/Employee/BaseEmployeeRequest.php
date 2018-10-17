<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge($this->additionalRules(),[
            'salary' => 'required',
            'bonus_percentage' => 'numeric',
            'name' => 'required',
        ]);
    }

    abstract public function additionalRules() : array;
}
