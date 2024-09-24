<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Log;
use Session;
use App\Http\Requests\AddPropertyRequest;
use App\Models\Property;
use App\Models\Bedroom;
use App\Models\Bathroom;
use App\Models\Sleep;
use App\Models\Housekeeper;
use App\Models\HotTub;
use App\Models\Amenity;
use App\Models\Season;
use App\Models\SeasonsRate;
use App\Models\PropertyAmenity;
use App\Models\Owner;
use App\Models\Attachment;
use App\Models\Reservation;
use App\helpers\Calendar;
use App\Models\Type;
use App\Services\PropertyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    protected $propertyService;
    
    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // $seasons = Season::all();
            $seasons = [];
            $search = $request->search;
            
            $properties = $this->propertyService->getPropertiesBySearch($search);
            
            return view('admin.property.index', compact('properties', 'seasons', 'search'));
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
        try {
            $bedrooms = Bedroom::all();
            $bathrooms = Bathroom::all();
            // $sleeps = Sleep::all();
            // $house_keepers  = Housekeeper::all();
            // $hot_tubs = HotTub::all();
            $sleeps = [];
            $house_keepers  = [];
            $hot_tubs = [];
            $amenities = Amenity::orderBy('display_order')->get();
            // $seasons = Season::orderBy('display_order')->get();
            $seasons = [];
            // $owners = Owner::all();
            $owners = [];
            // $types = Type::all();
            $types = [];

            return view('admin.property.create')
                ->with('bedrooms', $bedrooms)
                ->with('bathrooms', $bathrooms)
                ->with('sleeps', $sleeps)
                ->with('house_keepers', $house_keepers)
                ->with('hot_tubs', $hot_tubs)
                ->with('amenities', $amenities)
                ->with('seasons', $seasons)
                ->with('owners', $owners)
                ->with('types', $types);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddPropertyRequest $request)
    {
        try {
            // Begin transaction in case there are multiple database operations
            DB::beginTransaction();

            // Create new Property instance
            $property = new Property();
            $property->title = $request->get('title');
            $property->category_id = $request->get('category_id');
            $property->bedroom_id = $request->get('bedroom_id');
            $property->bathroom_id = $request->get('bathroom_id');
            $property->sleep_id = $request->get('sleep_id');

            // Handle boolean fields with a default value of 0 if not present
            $property->status = $request->get('status', 0);
            $property->is_featured = $request->get('is_featured', 0);
            $property->is_pet_friendly = $request->get('is_pet_friendly', 0);
            $property->is_online_booking = $request->get('is_online_booking', 0);

            // Handle numeric fields
            $property->commision = nl2zero($request, 'commision');
            $property->clearing_fee = $request->get('clearing_fee');
            $property->clearing_fee_active = nl2zero($request, 'clearing_fee_active');
            $property->pet_fee = $request->get('pet_fee');
            $property->pet_fee_active = nl2zero($request, 'pet_fee_active');
            $property->lodger_tax = nl2zero($request, 'lodger_tax');
            $property->lodger_tax_active = nl2zero($request, 'lodger_tax_active');
            $property->sales_tax = $request->get('sales_tax');
            $property->sales_tax_active = nl2zero($request, 'sales_tax_active');
            $property->is_calendar_active = nl2zero($request, 'is_calendar_active');

            // Handle foreign key relationships
            $property->housekeeper_id = $request->get('housekeeper_id');
            $property->hottub_id = $request->get('hottub_id');
            $property->owner = $request->get('owner');

            // Set property type flags
            $property->is_vacation = $request->get('property_type') === 'is_vacation' ? 1 : 0;
            $property->is_long_term = $request->get('property_type') === 'is_long_term' ? 1 : 0;

            // Descriptions and order
            $property->short_description = $request->get('short_description');
            $property->long_description = $request->get('long_description');
            $property->display_order = $request->get('display_order');

            // Save property and get ID
            $property->save();
            $property_id = $property->id;

            // Handle pictures (attachments)
            $pictures = $request->get('pictures', []);
            foreach ($pictures as $i => $pictureId) {
                $attachment = Attachment::find($pictureId);
                if ($attachment) {
                    $attachment->property_id = $property_id;
                    $attachment->title = $request->get('pic_title')[$i];
                    $attachment->order = $request->get('order')[$i];
                    $attachment->main = $request->get('main') == $pictureId ? 1 : 0;
                    $attachment->status = 1;
                    $attachment->save();
                }
            }

            // Handle PDF file upload
            if ($request->hasFile('pdf')) {
                $this->attachNewPDFFile($request, $property);
            }

            // Add season rates
            $this->addSeasonRates($request, $property_id);

            // Handle property amenities
            PropertyAmenity::where('property_id', $property_id)->delete();
            $all_amenities = Amenity::orderBy('display_order')->get();
            foreach ($all_amenities as $amenity) {
                $propertyAmenity = new PropertyAmenity();
                $propertyAmenity->amenity_id = $amenity->id;
                $propertyAmenity->property_id = $property_id;
                $propertyAmenity->value = in_array($amenity->id, $request->input('amenities', [])) ? 1 : 0;
                $propertyAmenity->save();
            }

            // Commit the transaction after all operations
            DB::commit();

            // Flash success message and redirect
            Session::flash('success', 'Property added successfully.');
            return redirect()->route('admin.property.index');

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error message
            Log::error('Error storing property: ' . $e->getMessage());

            // Flash error message and redirect back
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    private function attachNewPDFFile($request, $property)
    {
        $attachment = new Attachment;
        $attachment->property_id =  $property->id;
        $folder = public_path('uploads/properties');
        if (!$folder) {
            mkdir($folder, 666, true);
        }
        $name = $_FILES['pdf']['name'];
        $name = str_replace(' ', '_', $name);
        $request->file('pdf')->move($folder, $name);
        $attachment->filename = $name;
        $attachment->status =  1;
        $attachment->save();
    }

    private function addSeasonRates($request, $property_id)
    {

        //dd($request->all());

        $seasons = $request['season_id'];
        $x = 0;
        if ($seasons != null) {
            foreach ($seasons as $season) {
                if (!isset($request['daily_rate'][$x])) {

                    $data['daily_rate'][$x] = '0.00';

                    $request->merge($data);
                }

                if (!isset($request['weekly_rate'][$x])) {

                    $data['weekly_rate'][$x] = '0.00';

                    $request->merge($data);
                }

                if (!isset($request['monthly_rate'][$x])) {

                    $data['monthly_rate'][$x] = '0.00';

                    $request->merge($data);
                }

                if (!isset($request['deposit'][$x])) {

                    $data['deposit'][$x] = '0.00';

                    $request->merge($data);
                }


                //if($request['daily_rate'][$x]!="")
                //{
                $season_rate = new SeasonsRate();
                $season_rate->season_id = $season;
                $season_rate->property_id = $property_id;
                $season_rate->daily_rate = $request['daily_rate'][$x];
                $season_rate->weekly_rate = $request['weekly_rate'][$x];
                $season_rate->monthly_rate = $request['monthly_rate'][$x];
                $season_rate->deposit =  $request['deposit'][$x];
                $season_rate->save();
                //}
                $x++;
            }
        }
    }

    private function addAmenities($request, $property_id)
    {

        $amenities = $request['amenity_id'];

        $x = 0;
        if ($amenities != null) {
            foreach ($amenities as $amenity) {
                $property_amenity = new PropertyAmenity();
                $property_amenity->amenity_id = $amenity;
                $property_amenity->property_id = $property_id;

                if (isset($request['value-' . $amenity]))
                    $property_amenity->value = $request['value-' . $amenity];
                else
                    $property_amenity->value = 0;

                $property_amenity->save();

                $x++;
            }
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
        $reservations = null;
        $property = Property::find($id);

        if (isset($_GET['status']) && $_GET['status'] === 'archived') {
            $reservations = $property->reservations()
                ->where('arrival', '<', Carbon::now())
                ->get();
        } else {
            $reservations = $property->reservations()
                ->where('arrival', '>=', Carbon::now())
                ->get();
        }

        return view('admin.property.show', compact('property', 'reservations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $property = Property::find($id);

        $bedrooms = Bedroom::all();
        $bathrooms = Bathroom::all();
        $sleeps = Sleep::all();
        $house_keepers  = Housekeeper::all();
        $hot_tubs = HotTub::all();
        $amenities = Amenity::orderBy('display_order')->get();
        $seasons = Season::orderBy('display_order')->get();
        $owners = Owner::all();
        $users = user::all();
        $types = Type::all();


        return view('admin.property.edit')
            ->with('property', $property)
            ->with('bedrooms', $bedrooms)
            ->with('bathrooms', $bathrooms)
            ->with('sleeps', $sleeps)
            ->with('house_keepers', $house_keepers)
            ->with('hot_tubs', $hot_tubs)
            ->with('amenities', $amenities)
            ->with('seasons', $seasons)
            ->with('owners', $owners)
            ->with('types', $types)
            ->with('users', $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSeasonRates(Request $request, $id)
    {
        //first delete old enteries
        SeasonsRate::where('property_id', $id)->delete();

        $this->addSeasonRates($request, $id);

        Session::flash('success', 'Property season rates updated successfully.');
        return redirect('admin/property');
    }

    public function updateAmenities(Request $request, $id)
    {

        //first delete old enteries
        PropertyAmenity::where('property_id', $id)->delete();
        $this->addAmenities($request, $id);

        Session::flash('success', 'Property amenities updated successfully.');
        return redirect('admin/property');
    }

    public function update(AddPropertyRequest $request, $id)
    {

        $property = Property::find($id);

        $property->title = $request->get('title');

        $property->category_id = $request->get('category_id');



        $property->bedroom_id = $request->get('bedroom_id');
        $property->bathroom_id = $request->get('bathroom_id');
        $property->sleep_id = $request->get('sleep_id');

        $property->status = ($request->get('status')) !== null ? $request->get('status') : 0;
        $property->is_featured = ($request->get('is_featured')) !== null ? $request->get('is_featured') : 0;
        $property->is_pet_friendly = ($request->get('is_pet_friendly')) !== null ? $request->get('is_pet_friendly') : 0;
        $property->is_online_booking = ($request->get('is_online_booking')) !== null ? $request->get('is_online_booking') : 0;

        $property->commision = $request->get('commision');
        $property->housekeeper_id = $request->get('housekeeper_id');
        $property->hottub_id = $request->get('hottub_id');

        $property->clearing_fee = $request->get('clearing_fee');
        $property->clearing_fee_active = nl2zero($request, 'clearing_fee_active');

        $property->pet_fee = $request->get('pet_fee');
        $property->pet_fee_active = nl2zero($request, 'pet_fee_active');

        $property->lodger_tax = nl2zero($request, 'lodger_tax');
        $property->lodger_tax_active = nl2zero($request, 'lodger_tax_active');

        $property->sales_tax = $request->get('sales_tax');
        $property->sales_tax_active = nl2zero($request, 'sales_tax_active');

        $property->is_calendar_active = nl2zero($request, 'is_calendar_active');


        $property->owner = $request->get('owner');

        if ($request->get('property_type') == 'is_vacation') {

            $property->is_vacation = '1';
        } else {

            $property->is_vacation = '0';
        }

        if ($request->get('property_type') == 'is_long_term') {

            $property->is_long_term = '1';
        } else {

            $property->is_long_term = '0';
        }

        $property->short_description = $request->get('short_description');
        $property->long_description = $request->get('long_description');
        $property->display_order = $request->get('display_order');


        $property->save();

        $pictures = $request->get('pictures');


        if (!empty($pictures)) {

            for ($i = 0; $i < sizeof($pictures); $i++) {
                $attachment = Attachment::find($pictures[$i]);
                $attachment->property_id =  $property->id;
                $attachment->title =  $request->get('pic_title')[$i];
                $attachment->order =  $request->get('order')[$i];
                $attachment->main =  (null != $request->get('main') && $request->get('main') == $pictures[$i]) ? 1 : 0;
                $attachment->status =  1;
                $attachment->save();
            }
        }

        //$this->updateAmenities($request, $property->id);

        Session::flash('success', 'Property updated successfully.');
        return redirect('admin/property');
    }

    /**
     * Display the specified resource in calendar form
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCalendar($id)
    {
        $property = Property::find($id);

        $calendar = Calendar::RenderCalendar($property);

        return view('admin.property.calendar')
            ->with('calendar', $calendar)
            ->with('property', $property);
    }

    public function destroy($id)
    {
        Property::destroy($id);
        return response()->json('success');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePictures(Request $request, $id)
    {
        $property = Property::query()->findOrFail($id);

        $pictures = $request->get('pictures');

        if (!empty($pictures)) {

            for ($i = 0; $i < sizeof($pictures); $i++) {
                $attachment = Attachment::find($pictures[$i]);
                $attachment->property_id =  $property->id;
                $attachment->title =  $request->get('pic_title')[$i];
                $attachment->order =  $request->get('order')[$i];
                $attachment->main =  (null != $request->get('main') && $request->get('main') == $pictures[$i]) ? 1 : 0;
                $attachment->status =  1;
                $attachment->save();
            }
        }

        Session::flash('success', 'Pictures updated successfully.');
        return back();
    }
}
