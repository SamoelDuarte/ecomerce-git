<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // 'first_name' => 'required',
            // 'last_name' => 'required',
            'shop_name' => 'required|max:255',
            'username' => 'required|max:10',
            'password' => 'required|min:8',
            'email' => 'required|email',
            'phone' => 'required|max:16',
            'city' => 'required|max:255',
            'country' => 'required|max:255',
            'price' => 'required',
            'payment_method' => $this->price != 0 ? 'required' : '',
            'receipt' => $this->is_receipt == 1 ? 'required | mimes:jpeg,jpg,png' : '',
            'stripeToken' => $this->payment_method == 'Stripe' ? 'required' : '',
            'opaqueDataDescriptor' => 'sometimes|required',
            'post_code' => $this->payment_method == 'Iyzico' ? 'required' : '',
            'identity_number' => $this->payment_method == 'Iyzico' ? 'required' : '',
        ];
    }

    public function messages(): array
    {
        return [
            'receipt.required' => 'The receipt field image is required when instruction required receipt image'
        ];
    }
}
