<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Log; 
use Session;

use App\Http\Requests\AddHouseKeeperRequest;
use App\Models\HouseKeeper;

class HouseKeeperController extends Controller
{
    public $states;
    public function __construct() {
        $this->states = [
            'Washington DC',
            'Ohio',
            'Arizona',
            'Alaska',
            'Alkansas',
            'Alabama',
            'California',
            'Florida',
            'Georgea',
            'Hawaii',
            'Indiana',
        ];
    }


	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $house_keepers = HouseKeeper::all();
            return view('admin.house_keeper.index')->with('house_keepers', $house_keepers);
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
        return view('admin.house_keeper.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddHouseKeeperRequest $request)
    {
        try {
            $house_keeper = new HouseKeeper();
            $house_keeper->first_name = $request->get('first_name');
            $house_keeper->last_name = $request->get('last_name');
            $house_keeper->email = $request->get('email');
            $house_keeper->address1 = $request->get('address1') ?? '';
            $house_keeper->address2 = $request->get('address2') ?? '';
            $house_keeper->city = $request->get('city') ?? '';
            $house_keeper->state = $request->get('state') ?? '';
            $house_keeper->zip_code = $request->get('zip_code') ?? '';
            $house_keeper->phone = $request->get('phone');
            $house_keeper->notes = $request->get('notes') ?? '';
            $house_keeper->display_order = $request->get('display_order') ?? 0;
            $house_keeper->save();
            
            return ResponseHelper::jsonResponse('success', 'Housekeeper added successfully.', route('admin.housekeepers.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $house_keeper = HouseKeeper::find($id);
            return view('admin.house_keeper.show')->with('house_keeper', $house_keeper);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
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
            $house_keeper = HouseKeeper::find($id);
            return view('admin.house_keeper.edit')->with('house_keeper', $house_keeper);
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
    public function update(AddHouseKeeperRequest $request, $id)
    {
        try {
            $house_keeper = HouseKeeper::find($id);
            $house_keeper->first_name = $request->get('first_name');
            $house_keeper->last_name = $request->get('last_name');
            $house_keeper->email = $request->get('email');
            $house_keeper->address1 = $request->get('address1') ?? '';
            $house_keeper->address2 = $request->get('address2') ?? '';
            $house_keeper->city = $request->get('city') ?? '';
            $house_keeper->state = $request->get('state') ?? '';
            $house_keeper->zip_code = $request->get('zip_code') ?? '';
            $house_keeper->phone = $request->get('phone');
            $house_keeper->notes = $request->get('notes') ?? '';
            $house_keeper->display_order = $request->get('display_order') ?? 0;
            $house_keeper->save();
            
            return ResponseHelper::jsonResponse('success', 'Housekeeper updated successfully.', route('admin.housekeepers.index'));
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
            HouseKeeper::destroy($id);
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }   
}