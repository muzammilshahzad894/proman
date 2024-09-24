<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSeasonRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   public function rules()
    {
        return [
            'title' => 'required',		
			'from_month' => 'required',
            'from_day' => 'required',
            'to_month' => 'required',
            'to_day' => 'required',
            'type' => 'required'
        ];
    }
}
