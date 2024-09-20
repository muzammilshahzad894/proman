<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $email_templates = EmailTemplate::orderBy('type',)->get()->groupBy('type');
        return view('email_templates.index')->with('email_templates', $email_templates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('email_templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required',
                'subject' => 'required',
            ]);

            EmailTemplate::updateOrCreate(['id' => $request->id], $request->except('_token', 'id'));        
            if (@$request->id) {
                Session::flash('success', 'Updated Successfully');
            } else {
                Session::flash('success', 'Added Successfully');
            }
            return redirect('admin/email_templates/1/edit');        

        } catch (\Throwable $th) {
            return $th;
            return response()->json(['error' => $th->getMessage()], 500);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailTemplate $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailTemplate $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $emailTemplate = EmailTemplate::query()->find($id);

        if (@request()->user()->email=='programmer@rezosystems.com') {
            $readonly="";
        }else{
            $readonly="readonly";
        }

        return view('email_templates.edit', compact('emailTemplate','readonly'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\EmailTemplate $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailTemplate $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            EmailTemplate::destroy($id);
            return response()->json('success', 200);
        } catch (\Exception $e) {
            return response()->json('error', $e->getCode());
        }
    }


}
