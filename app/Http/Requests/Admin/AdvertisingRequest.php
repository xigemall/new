<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvertisingRequest extends FormRequest
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
            'title' => [
                'required',
                'max:100',
            ],
            'img' => [
                'file',
                'image',
            ],
            'link' => [
                'max:255',
                'url',
                'string',
                'required',
            ],
            'place' => [
                'required',
                Rule::in([0, 1])
            ],
            'site' => [
                'array',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'title' => '广告标题',
            'img' => '广告图片',
            'link' => '链接',
            'place' => '位置',
            'site' => '网站',
        ];
    }
}
