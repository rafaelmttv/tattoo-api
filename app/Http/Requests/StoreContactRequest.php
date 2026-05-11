<?php

namespace App\Http\Requests;

use App\Enums\ContactType;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'contactable_type' => 'required|string|in:App\Models\Customer,App\Models\TattooArtist,App\Models\Studio',
            'contactable_id' => 'required|integer',
            'type' => ['required', 'string', ContactType::validationRule()],
            'value' => 'required|string|max:500',
        ];
    }
}
