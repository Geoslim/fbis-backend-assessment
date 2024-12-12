<?php

namespace App\Http\Requests\Transaction;

use App\Enums\NetworkProviders;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendingAirtimeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'recipient' => ['required', 'string'],
            'amount' => [
                'required',
                'numeric',
                'min:' . config('vending.minimum_amount'),
                'max:' . config('vending.maximum_amount'),
            ],
            'network' => ['required', 'string', Rule::in(NetworkProviders::cases())],
        ];
    }

    public function messages(): array
    {
        return [
            'network.in' => 'The selected network provider is not supported. Kindly select one of '
                . implode(', ', array_map(fn($provider) => $provider->value, NetworkProviders::cases())),

            'amount.min' => 'The minimum amount allowed is ' . config('vending.minimum_amount'),
            'amount.max' => 'The maximum amount allowed is ' . config('vending.maximum_amount'),
        ];
    }
}
