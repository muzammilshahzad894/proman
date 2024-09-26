<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use Session;
use Cache;
use File;
use Input;
use Validator;
use App\Models\Attachment;

class AttachmentController extends Controller
{
    public function save(Request $request)
    {
        $input = $request->all();

        $errormsg = 'File type is not supported.';
        $validator = Validator::make(
            $input,
            ['file' => ['mimes:jpeg,bmp,png,pdf,doc,docx,xls,xlxs,pptx,ppt,zip,rar,txt,jpg,JPG']]
        );

        if ($validator->fails()) {
            return response($errormsg, 500);
        }

        // upload files
        $name = $request->file('file')->getClientOriginalName();
        $name = strtolower($name);

        $destinationPath =  public_path() . '/uploads/properties';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }
        $fileName = rand(11111, 99999) . '-' . $name;
        if ($request->hasFile('file')) {
            $request->file('file')->move($destinationPath, $fileName);

            $attachment = new Attachment;
            $attachment->title = $request->get('title');
            $attachment->order = (null !== $request->get('order')) ? $request->get('order') : 0;
            $attachment->main = (null !== $request->get('main')) ? $request->get('main') : 0;
            $attachment->filename = $fileName;
            $attachment->status = '0';
            $attachment->property_id = isset($request->property_id) ? $request->property_id : '';
            $attachment->save();

            $response = [
                'status' => 'success',
                'attachment' => $attachment
            ];

            return response()->json($response, 200);
        }


        return response('error uploading file.', 500);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $file = Attachment::query()->findOrFail($id);
        $file->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deleted successfully.'
        ]);
    }
}
