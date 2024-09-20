<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Log;
use Session;

use App\Http\Requests\AddTypeRequest;
use App\Models\Type;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $types = Type::orderby('display_order', 'asc')->get();
            return view('admin.type.index')->with('types', $types);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddTypeRequest $request)
    {
        try {
            $type = new Type();
            $type->title = $request->get('title');
            $type->display_order = $request->get('display_order');
            $type->save();

            $type->image = $this->uploadImage($request, $type->id);
            $type->save();
            
            return ResponseHelper::jsonResponse('success', 'Amenity created successfully.', route('admin.types.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }


    private function uploadImage($request, $type_id)
    {
        $file = $request->file('image');
        if ($file !== null) {
            $filename  = $type_id . '_thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('types');
            $file->move($path, $filename);

            //Image::make($image->getRealPath())->resize(200, 200)->save($path);
            return $filename;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $type = Type::find($id);
            return view('admin.type.edit')->with('type', $type);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddTypeRequest $request, $id)
    {
        try {
            $type = Type::find($id);
            $type->title = $request->get('title');
            $type->display_order = $request->get('display_order');
            $type->save();

            $type->image = $this->uploadImage($request, $type->id);
            $type->save();

            return ResponseHelper::jsonResponse('success', 'Amenity updated successfully.', route('admin.types.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Type::destroy($id);
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }
}
