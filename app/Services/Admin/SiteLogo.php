<?php


namespace App\Services\Admin;


use Illuminate\Support\Facades\Storage;

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

    /**
     * 移动网站LOGO临时文件到正式目录下
     * @param string $fileName
     */
    protected function moveTmpLogoFile(string $fileName)
    {
        $oldFile = str_replace('uploads/', '', $fileName);
        $newFile = str_replace('/tmp', '', $oldFile);
        Storage::disk('admin')->move($oldFile, $newFile);
        return 'uploads/'.$newFile;
    }
}