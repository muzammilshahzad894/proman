<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Log; 
use Session;

use App\Http\Requests\AddSleepRequest;
use App\Models\Sleep;

class SleepController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sleeps = Sleep::orderby('display_order')->get();
            return view('admin.sleeps.index')->with('sleeps', $sleeps);
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
        return view('admin.sleeps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddSleepRequest $request)
    {
        try {
            $sleep = new Sleep();
            $sleep->title = $request->get('title');
            $sleep->display_order = $request->get('display_order');
            $sleep->save();
            
            return ResponseHelper::jsonResponse('success', 'Sleep added successfully.', route('admin.sleeps.index'));
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
            $sleep = Sleep::find($id);
            return view('admin.sleeps.edit')->with('sleep', $sleep);
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
    public function update(AddSleepRequest $request, $id)
    {
        try {
            $sleep = Sleep::find($id);
            $sleep->title = $request->get('title');
            $sleep->display_order = $request->get('display_order');
            $sleep->save();
            
            return ResponseHelper::jsonResponse('success', 'Sleep updated successfully.', route('admin.sleeps.index'));
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
            Sleep::destroy($id);
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }
	
    
}
