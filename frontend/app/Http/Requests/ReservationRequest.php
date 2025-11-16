<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
        return [
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],
            'customer_phone' => [
                'required',
                'string',
                'max:20',
            ],
            'customer_email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'reservation_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'reservation_time' => [
                'required',
                'date_format:H:i',
            ],
            'guests_count' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
            'table_id' => [
                'required',
                'integer',
                'exists:tables,id',
            ],
            'shift' => [
                'required',
                Rule::in(['lunch', 'dinner']),
            ],
            'status' => [
                'required',
                Rule::in(['pending', 'confirmed', 'cancelled', 'completed']),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Il nome del cliente è obbligatorio.',
            'customer_name.max' => 'Il nome del cliente non può superare i 255 caratteri.',
            'customer_phone.required' => 'Il numero di telefono è obbligatorio.',
            'customer_phone.max' => 'Il numero di telefono non può superare i 20 caratteri.',
            'customer_email.email' => 'L\'indirizzo email non è valido.',
            'customer_email.max' => 'L\'indirizzo email non può superare i 255 caratteri.',
            'reservation_date.required' => 'La data della prenotazione è obbligatoria.',
            'reservation_date.date' => 'La data della prenotazione non è valida.',
            'reservation_date.after_or_equal' => 'La data della prenotazione non può essere nel passato.',
            'reservation_time.required' => 'L\'orario della prenotazione è obbligatorio.',
            'reservation_time.date_format' => 'L\'orario deve essere nel formato HH:MM.',
            'guests_count.required' => 'Il numero di ospiti è obbligatorio.',
            'guests_count.integer' => 'Il numero di ospiti deve essere un numero intero.',
            'guests_count.min' => 'Il numero di ospiti deve essere almeno 1.',
            'guests_count.max' => 'Il numero di ospiti non può superare 20.',
            'table_id.required' => 'Il tavolo è obbligatorio.',
            'table_id.exists' => 'Il tavolo selezionato non è valido.',
            'shift.required' => 'Il turno è obbligatorio.',
            'shift.in' => 'Il turno deve essere "pranzo" o "cena".',
            'status.required' => 'Lo stato è obbligatorio.',
            'status.in' => 'Lo stato selezionato non è valido.',
            'notes.max' => 'Le note non possono superare i 1000 caratteri.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'nome cliente',
            'customer_phone' => 'telefono',
            'customer_email' => 'email',
            'reservation_date' => 'data prenotazione',
            'reservation_time' => 'orario',
            'guests_count' => 'numero ospiti',
            'table_id' => 'tavolo',
            'shift' => 'turno',
            'status' => 'stato',
            'notes' => 'note',
        ];
    }
}
