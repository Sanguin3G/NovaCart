<?php

namespace App\Http\Requests\Customer\Checkout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProcessCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Define base rules
        $rules = [
            'shipping_address' => ['required', 'string', 'min:10', 'max:1000'],
            'billing_address' => ['nullable', 'string', 'min:10', 'max:1000'],
            'payment_method' => ['required', 'string', 'in:credit_card,paypal,bank_transfer'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'receive_email_confirmation' => ['nullable', 'boolean'],
        ];

        // Add rules for guest users
        if (!Auth::check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
        } else {
            // For logged-in users, name and email are optional if they differ from their account info
            $rules['name'] = ['nullable', 'string', 'max:255'];
            $rules['email'] = ['nullable', 'string', 'email', 'max:255'];
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'receive_email_confirmation' => $this->toBoolean($this->receive_email_confirmation),
        ]);
    }

    /**
     * Convert a value to a boolean.
     *
     * @param  mixed  $value
     * @return bool
     */
    private function toBoolean($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }
}
