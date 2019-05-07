<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WechatRequest extends FormRequest
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
                'string',
                'max:50',
                'required',
                Rule::unique('wechats')->ignore(request()->route('wechat'))
            ],
            'wechat_num' => [
                'string',
                'max:255',
                'required',
            ],
            'site' => [
                'array',
                'required'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'wechat_num' => '公众号',
            'site' => '网站',
        ];
    }
}
