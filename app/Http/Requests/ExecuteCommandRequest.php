<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;

class ExecuteCommandRequest extends BaseAsyncRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool{
        return true;
    }


    protected function prepareForValidation(){
        Log::info('Execute command request. Inputs:'.$this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array{
        return [
            'command' => ['required', function ($attribute, $value, $fail) {
                    if (preg_match('/\b\*+\b/', $value)) {
                        $fail('Fuck you too.');
                    }
                },
            ]
        ];
    }

    public function messages(): array{
        return [
            'command.required' => 'Dave we have communication problems'
        ];
    }
}
