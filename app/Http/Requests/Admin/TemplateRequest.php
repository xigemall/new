<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('templates')->ignore(request('id'))
            ],
            'description' => [
                'string',
                'nullable',
                'max:255',
            ],
            'file' => [
                'required',
                'file',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => '模板名称',
            'description' => '模板描述',
            'file' => '模板文件',
        ];
    }
}
