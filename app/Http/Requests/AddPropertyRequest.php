<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPropertyRequest extends FormRequest
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
            'category_id' => 'required',
            'short_description' => 'required',
            'display_order' => 'required',
            'display_order' => 'numeric',
            'status' => 'required',
            'is_featured' => 'required',
            'is_pet_friendly' => 'required',
            'is_online_booking' => 'required',
            'pdf' => 'sometimes|mimes:pdf'
        ];
    }

	public function messages()
	{
		return [
         'pdf.mimes'                  => 'File must be of pdf format',
			'is_featured.required'       => 'Mention the fetured checkbox',
			'is_pet_friendly.required'   => 'Mention pet friendly checkbox',
			'is_online_booking.required' => 'Mention online booking checkbox',
		];
	}

}