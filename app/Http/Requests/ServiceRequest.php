<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            "organisation_id" => ["required"],
            "name" => ["required", "string", "max:200"],
            "description" => ["required", "string"],
            "email" => ["email", "max:100"],
            "url" => ["string", "max:300"],
            "status" => ["required"],
            "languages" => ["array"],
            "deliverable_type" => ["required"],
            "attending_type" => ["required"],
            "attending_access" => ["required"],
            "wait_time" => ["max:300"],
            "referral_process" => ["max:500"],
        ];
    }
}
