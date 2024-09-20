<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityDropdownValue;
use App\Http\Requests\AddAmenityRequest;
use App\Helpers\ResponseHelper;
use Log;
use Session;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $amenities = Amenity::orderby('display_order')->get();
            return view('admin.amenities.index', compact('amenities'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.amenities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddAmenityRequest $request)
    {
        try {
            $amenity = new Amenity();
            $amenity->title = $request->get('title');
            $amenity->type = $request->get('type');
            $amenity->group = $request->get('group');
            $amenity->display_order = $request->get('display_order');
            $amenity->created_by = 1;

            if ($request->type == 'CheckBox' || $request->type == 'Dropdown') {
                $amenity->option = ($request->option) ? json_encode($request->option) : null;
            }

            $amenity->save();

            $this->addDropdownValues($request, $amenity->id);

            return ResponseHelper::jsonResponse('success', 'Amenity created successfully.', route('admin.amenities.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }

    private function addDropdownValues($request, $amenity_id)
    {
        $values = $request->get('values');
        if ($values != null) {
            //first delete old dropdown values
            Amenity::find($amenity_id)->dropdownValues()->delete();
            foreach ($values as $value) {
                $dropdown_value = new AmenityDropdownValue();
                $dropdown_value->amenity_id = $amenity_id;
                $dropdown_value->value = $value;
                $dropdown_value->save();
            }
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
            $amenity = Amenity::find($id);
            return view('admin.amenities.edit', compact('amenity'));
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
    public function update(AddAmenityRequest $request, $id)
    {
        try {
            $amenity = Amenity::find($id);
            $amenity->title = $request->get('title');
            $amenity->type = $request->get('type');
            $amenity->group = $request->get('group');
            $amenity->display_order = $request->get('display_order');

            if ($request->type == 'CheckBox' || $request->type == 'Dropdown') {
                $amenity->option = ($request->option) ? json_encode($request->option) : null;
            }

            $amenity->save();

            $this->addDropdownValues($request, $amenity->id);

            return ResponseHelper::jsonResponse('success', 'Amenity updated successfully.', route('admin.amenities.index'));
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
            Amenity::destroy($id);
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }
}
