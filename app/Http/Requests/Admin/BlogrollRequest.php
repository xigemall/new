<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogrollRequest extends FormRequest
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
            'title'=>[
                'string',
                'max:50',
                'required'
            ],
            'link'=>[
                'string',
                'max:255',
                'url'
            ],
            'place'=>[
                Rule::in(0,1)
            ],
            'site'=>[
                'array'
            ]
        ];
    }

    public function attributes()
    {
        return [
          'title'=>'标题',
          'link'=>'链接地址',
          'place'=>'位置',
          'site'=>'网站',
        ];
    }
}
