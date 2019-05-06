<?php


namespace App\Services\Admin;


use Illuminate\Support\Facades\Storage;

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

    /**
     * 网站ico临时文件移入正式路径
     * @param string $fileName
     */
    public function moveTmpIcoFile(string $fileName)
    {
        $oldFile = str_replace('uploads/', '', $fileName);
        $newFile = str_replace('/tmp', '', $oldFile);
        Storage::disk('admin')->move($oldFile, $newFile);
        return 'uploads/'.$newFile;
    }
}