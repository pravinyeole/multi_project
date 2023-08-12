<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        // dd($this->request);
        return [
            'group_name'           => 'required|string',
            'group_name'           => Rule::unique('group', 'group_name')->ignore($this->request->get('group_id'), 'group_id'),
        ];
    }

    public function messages(){
        return [
            'group_name.required'  => 'Please enter name.',
        ];
    }
}
