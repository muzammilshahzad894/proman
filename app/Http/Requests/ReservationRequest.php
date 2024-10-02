<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
        $request = $this->all();

        $rules = [
            'adults' => 'required',
            'property_id' => 'required',
            'credit_card' => 'required_if:payment_mode,==,credit card',
            'cvv' => 'required_if:payment_mode,==,credit card',
            'housekeeper_id' => 'required',
        ];

        if (isset($request['returning_customer_checkbox'])  &&  !empty($request['returning_customer_checkbox'])) {
            $rules['customer_id'] = 'required';
        } else {
            $rules['first_name'] = 'required';
            $rules['last_name'] = 'required';
            $rules['email'] = 'required|email';
            $rules['phone'] = 'required';
        }

        return $rules;
    }
}
