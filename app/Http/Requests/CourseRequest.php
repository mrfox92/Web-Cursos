<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; //  utilizamos reglas de validaciones
use App\Role;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //  Nos aseguramos que quien realiza la operacion tiene role teacher
        return auth()->user()->role_id === Role::TEACHER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch( $this->method() ) {
            case 'GET':
            case 'DELETE':
                return [];
            
            case 'POST': {
                return [
                    'name'              =>  'required|min:5',
                    'description'       =>  'required|max:300',
                    'level_id'          =>  ['required', Rule::exists('levels', 'id')],
                    'category_id'       =>  ['required', Rule::exists('categories', 'id')],
                    'picture'           =>  'required|image|mimes:jpg,jpeg,png',
                    'requirements.0'    =>  'required_with:requirements.1', //  El campo requirements.0 es obligatorio cuando requirements.1 está presente.
                    'goals.0'           =>  'required_with:goals.1' //  El campo goals.0 es obligatorio cuando goals.1 está presente.

                ];
            }

            case 'PUT': {
                return [
                    'name'              =>  'required|min:5',
                    'description'       =>  'required|max:300',
                    'level_id'          =>  ['required', Rule::exists('levels', 'id')],
                    'category_id'       =>  ['required', Rule::exists('categories', 'id')],
                    'picture'           =>  'sometimes|image|mimes:jpg,jpeg,png',
                    'requirements.0'    =>  'required_with:requirements.1', //  El campo requirements.0 es obligatorio cuando requirements.1 está presente.
                    'goals.0'           =>  'required_with:goals.1' //  El campo goals.0 es obligatorio cuando goals.1 está presente.

                ];
            }
        }
    }
}
