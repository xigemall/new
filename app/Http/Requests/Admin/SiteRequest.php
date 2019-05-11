<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteRequest extends FormRequest
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
                'string',
                'required',
                'max:200',
            ],
            'description' => [
                'string',
                'required',
                'max:600'
            ],
            'keyword' => [
                'string',
                'required',
                'max:255',
            ],
            'domain' => [
                'string',
                'required',
                'max:255', Rule::unique('sites')->ignore(request()->route('id'))
            ],
            'logo' => [
                'file',
                'image'
            ],
            'ico' => [
                'file',
                'file'
            ],
            'template_id' => [
                'integer',
                'numeric',
                'required',
            ],
            'navigations' => [
                'string',
                'required'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'title' => '网站标题',
            'description' => '网站描述',
            'keyword' => '网站关键字',
            'domain' => '网站域名',
            'logo' => '网站LOGO图片',
            'ico' => '网站ICO',
            'template_id' => '模板选择',
            'navigations' => '网站栏目',
        ];
    }
}
