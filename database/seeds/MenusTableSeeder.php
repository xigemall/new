<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'parent_id' => 0,
                'order' => 0,
                'title' => '网站管理',
                'icon' => 'fa-bars',
                'uri' => null,
            ],
            [
                'parent_id' => 8,
                'order' => 0,
                'title' => '模板',
                'icon' => 'fa-bars',
                'uri' => 'template',
            ],
            [
                'parent_id' => 8,
                'order' => 0,
                'title' => '网站',
                'icon' => 'fa-bars',
                'uri' => 'site',
            ],
            [
                'parent_id' => 0,
                'order' => 0,
                'title' => '文章管理',
                'icon' => 'fa-bars',
                'uri' => 'article',
            ],
            [
                'parent_id' => 0,
                'order' => 0,
                'title' => '广告管理',
                'icon' => 'fa-bars',
                'uri' => 'advertising',
            ],
            [
                'parent_id' => 0,
                'order' => 0,
                'title' => '微信管理',
                'icon' => 'fa-bars',
                'uri' => 'wechat',
            ],
            [
                'parent_id' => 0,
                'order' => 0,
                'title' => '友情链接',
                'icon' => 'fa-bars',
                'uri' => 'blogroll',
            ],
        ];
        Encore\Admin\Auth\Database\Menu::insert($data);
    }
}
