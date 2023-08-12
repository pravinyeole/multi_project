<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Yoeunes\Toastr\Facades\Toastr;

use Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_fname' => 'required',
            'user_fname' => 'required',
            'password' => 'required',
            'email' => 'unique:users,email,NULL,id,deleted_at,NULL',
        ];
    }

    protected function withValidator(Validator $validator)
    {   
        foreach ($validator->errors()->all() as $error) {
            toastr()->error($error);
        }
    }
}
