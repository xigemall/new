<?php


namespace App\Services\Admin;


trait SiteIco
{
    /**
     * 上传网站ICO
     * @return string
     *
     */
    public function uploadSiteIco()
    {
        $file = request()->file('ico');
        $path = 'images/site/ico/tmp';
        $name = $this->getFileName();
        $extension = $file->getClientOriginalExtension();
        $newName = $name . '.' . $extension;
        $fileName = $file->storeAs($path, $newName, 'admin');
        return 'uploads/' . $fileName;
    }
}