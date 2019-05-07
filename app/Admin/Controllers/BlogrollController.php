<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogrollRequest;
use App\Models\Blogroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Blogroll::with('blogrollSites')->get();
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
    public function store(BlogrollRequest $request)
    {
        DB::transaction(function () use ($request, &$data) {
            $data = Blogroll::create($request->input());
            if ($request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));
                $data->blogrollSites()->createMany($site);
            }
        });
        return response()->json($data->load('blogrollSites'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Blogroll::with('blogrollSites')->findOrFail($id);
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
    public function update(BlogrollRequest $request, $id)
    {
        $data = Blogroll::findOrFail($id);
        DB::transaction(function () use ($request, &$data) {
            $data->update($request->input());
            $data->blogrollSites()->delete();
            if ($request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));
                $data->blogrollSites()->createMany($site);
            }
        });
        return response()->json($data->load('blogrollSites'), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Blogroll::findOrFail($id);
        $data->blogrollSites()->delete();
        $data->delete();
        return response()->json('', 204);
    }
}
