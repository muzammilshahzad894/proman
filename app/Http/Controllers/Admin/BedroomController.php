<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Log; 
use Session;

use App\Http\Requests\AddBedroomRequest;
use App\Models\Bedroom;

class BedroomController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $bedrooms = Bedroom::orderby('display_order')->get();
            return view('admin.bedroom.index')->with('bedrooms', $bedrooms);
        } catch (\Exception $e) {
            Log::error('BedroomController@index Error: ' . $e->getMessage());
            return redirect('admin/bedroom');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bedroom.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBedroomRequest $request)
    {
        try {
            $bedroom = new Bedroom();
            $bedroom->title = $request->get('title');
            $bedroom->display_order = $request->get('display_order');
            $bedroom->save();
            
            return ResponseHelper::jsonResponse('success', 'Bedroom added successfully.', route('admin.bedrooms.index'));
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
            $bedroom = Bedroom::find($id);
            return view('admin.bedroom.edit')->with('bedroom', $bedroom);
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
    public function update(AddBedroomRequest $request, $id)
    {
        try {
            $bedroom = Bedroom::find($id);
            $bedroom->title = $request->get('title');
            $bedroom->display_order = $request->get('display_order');
            $bedroom->save();
            
            return ResponseHelper::jsonResponse('success', 'Bedroom updated successfully.', route('admin.bedrooms.index'));
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
            Bedroom::destroy($id);
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }
}
