<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // যদি চাইলে authorization logic add করতে পারেন
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'contact'           => 'nullable|string|max:15',
            'secondary_contact' => 'nullable|string|max:15',
            'email'             => 'nullable|email|max:255',
            'address'           => 'nullable|string|max:500',
            'package'           => 'nullable|string|max:255',
            'username'          => 'required|string|max:255|unique:clients,username,' . $this->id,
            'password'          => $this->isMethod('post') ? 'required|string|min:6' : 'nullable|string|min:6',
            'Onu_mac'           => 'nullable|string|max:17',
            'onu_serial'        => 'nullable|string|max:255',
            'onu_brand'         => 'nullable|string|max:255',
            'onu_free'          => 'boolean',
            'onu_returned'      => 'boolean',
            'onu_owner'         => 'required|in:company,client',
            'cable'             => 'nullable|integer|min:0',
            'cable_returned'    => 'boolean',
            'cable_owner'       => 'required|in:company,client',
            'billing_type'      => 'required|in:prepaid,postpaid',
            'gps_location'      => 'nullable|string|max:255',
            'comment'           => 'nullable|string',
            'status'            => 'required|in:registered,expired',
            'expiration'        => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'This username is already taken.',
            'billing_type.in' => 'Billing type must be Prepaid or Postpaid.',
            'onu_owner.in'    => 'ONU owner must be Company or Client.',
            'cable_owner.in'  => 'Cable owner must be Company or Client.',
            'status.in'       => 'Status must be Registered or Expired.',
        ];
    }
}
