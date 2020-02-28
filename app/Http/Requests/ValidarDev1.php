<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidarDev1 extends FormRequest
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
        $id = $this->id;
        return [
            'github_username' => 'required|unique:devs,github_username,' . $id . '|max:191',
            'nome' => 'required',
        ];

        /*return [
            'github_username' => 'required|unique:devs,github_username|max:191',
            'nome' => 'required',
        ];*/
    }

    public function messages()
    {
        return [
            'github_username.required' => 'Campo obrigatório',
            'github_username.unique' => 'username já cadastrado por outro usuário',
            'github_username.max' => 'O campo deverá conter no máximo :max',
            'nome.required' => 'Campo obrigatório'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
