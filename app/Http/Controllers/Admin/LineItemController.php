<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Log;
use Session;

use App\Helpers\ResponseHelper;
use App\Http\Requests\LineItemRequest;
use App\Models\LineItem;

class LineItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $lineitems = LineItem::all();
            return view('admin.lineitem.index')
                ->with('lineitems', $lineitems);
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
        return view('admin.lineitem.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LineItemRequest $request)
    {
        try {
            $lineitem = new LineItem();
            $lineitem->title = $request->get('title');
            $lineitem->type = $request->get('type');
            $lineitem->percentage_apply_type = $request->get('percentage_apply_type');
            $lineitem->value = $request->value ?? 0;
            $lineitem->display_order = $request->get('display_order');
            $lineitem->save();

            return ResponseHelper::jsonResponse('success', 'Line item created successfully.', route('admin.lineitems.index'));
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
            $lineitem = LineItem::find($id);
            return view('admin.lineitem.edit')
                ->with('lineitem', $lineitem);
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
    public function update(LineItemRequest $request, $id)
    {
        try {
            $lineitem = lineItem::find($id);
            $lineitem->title = $request->get('title');
            $lineitem->type = $request->get('type');
            $lineitem->percentage_apply_type = $request->get('percentage_apply_type');
            $lineitem->value = $request->value ?? 0;
            $lineitem->display_order = $request->get('display_order');
            $lineitem->save();
            
            return ResponseHelper::jsonResponse('success', 'Line item updated successfully.', route('admin.lineitems.index'));
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
            $lineitem = LineItem::find($id);
            $lineitem->delete();
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json('error');
        }
    }
}
