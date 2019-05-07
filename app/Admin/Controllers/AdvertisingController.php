<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvertisingRequest;
use App\Models\Advertising;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdvertisingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Advertising::with('AdvertisingSites.site')->get();
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertisingRequest $request)
    {
        DB::transaction(function () use ($request, &$data) {
            // 移动广告图片地址
            $newImg = $this->moveImgFile($request->input('img'));
            $request->offsetSet('img', $newImg);

            $data = Advertising::create($request->input());
            if ($request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));

                $data->AdvertisingSites()->createMany($site);
            }
        });
        return response()->json($data->load('AdvertisingSites'), 201);

    }

    /**
     * 移动广告图片地址
     * @param string $img
     * @return string
     */
    protected function moveImgFile(string $img)
    {
        $oldPath = str_replace('uploads/', '', $img);
        $newPath = str_replace('/tmp', '', $oldPath);
        Storage::disk('admin')->move($oldPath, $newPath);
        return 'uploads/' . $newPath;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Advertising::with('AdvertisingSites.site')->findOrFail($id);
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdvertisingRequest $request, $id)
    {
        $data = Advertising::findOrFail($id);
        $request = $this->checkImg($request, $data);
        DB::transaction(function () use ($request, &$data) {
            $data->update($request->input());
            $data->AdvertisingSites()->delete();
            if ($request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));
                $data->AdvertisingSites()->createMany($site);
            }
        });
        return response()->json($data->load('AdvertisingSites'), 201);
    }

    protected function checkImg($request, $data)
    {
        if ($request->input('img') != $data->img) {
            $oldFile = str_replace('uploads/', '', $request->input('img'));
            $newFile = str_replace('/tmp', '', $oldFile);
            Storage::disk('admin')->move($oldFile, $newFile);
            $request->offsetSet('img', 'uploads/' . $newFile);
        }
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $data =  Advertising::findOrFail($id);
       $data->AdvertisingSites()->delete();
       $data->delete();
       return response()->json('',204);
    }

    /**
     * 上传广告图片
     * @param Request $request
     * @return string
     */
    public function uploadAdvertisingImg(Request $request)
    {
        $message = [
            'img' => '广告图片',
        ];
        $this->validate($request, [
            'img' => [
                'required',
                'file',
                'image',
            ]
        ], [], $message);
        $path = 'images/advertising/tmp';
        $extension = $request->file('img')->getClientOriginalExtension();
        $fileName = $this->getFileName();
        $name = $fileName . '.' . $extension;
        $file = $request->file('img')->storeAs($path, $name, 'admin');
        return 'uploads/' . $file;
    }

    protected function getFileName()
    {
        return date('YmdHis') . str_random(6);
    }
}
