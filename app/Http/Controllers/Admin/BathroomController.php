<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Log;
use Session;

use App\Http\Requests\AddBathroomRequest;
use App\Models\Bathroom;

class BathroomController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $bathrooms = Bathroom::orderby('display_order')->get();
            return view('admin.bathroom.index')->with('bathrooms', $bathrooms);
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
        return view('admin.bathroom.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBathroomRequest $request)
    {
        try {
            $bathroom = new Bathroom();
            $bathroom->title = $request->get('title');
            $bathroom->display_order = $request->get('display_order');
            $bathroom->save();
            
            return ResponseHelper::jsonResponse('success', 'Bathroom added successfully.', route('admin.bathrooms.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
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
            $bathroom = Bathroom::find($id);
            return view('admin.bathroom.edit')->with('bathroom', $bathroom);
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
    public function update(AddBathroomRequest $request, $id)
    {
        try {
            $bathroom = Bathroom::find($id);
            $bathroom->title = $request->get('title');
            $bathroom->display_order = $request->get('display_order');
            $bathroom->save();

            return ResponseHelper::jsonResponse('success', 'Bathroom updated successfully.', route('admin.bathrooms.index'));
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
            Bathroom::destroy($id);
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }
}
