<?php


namespace App\Services\Admin;


trait SiteLogo
{
    /**
     * 上传网站logo
     * @return string
     */
    public function uploadSiteLogo()
    {
        $file = request()->file('logo');
        $path = 'images/site/logo/tmp';
        $name = $this->getFileName();
        $extension = $file->getClientOriginalExtension();
        $newName = $name . '.' . $extension;
        $fileName = $file->storeAs($path, $newName, 'admin');
        return 'uploads/' . $fileName;
    }
}