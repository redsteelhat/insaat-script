<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public form, herkes erişebilir
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+90|0)?[5][0-9]{9}$/'], // TR telefon formatı
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            
            'project_type' => ['nullable', 'in:konut,ticari,endustriyel,tadilat'],
            'location_city' => ['nullable', 'string', 'max:255'],
            'location_district' => ['nullable', 'string', 'max:255'],
            'location_address' => ['nullable', 'string', 'max:500'],
            
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'room_count' => ['nullable', 'integer', 'min:0'],
            'floor_count' => ['nullable', 'integer', 'min:0'],
            'current_status' => ['nullable', 'in:arsa_var,proje_var,kaba_var,tadilat'],
            'requested_services' => ['nullable', 'array'],
            'requested_services.*' => ['in:mimari_proje,ic_mimari,tahhut,anahtar_teslim'],
            'budget_range' => ['nullable', 'string', 'max:255'],
            
            'source' => ['nullable', 'in:web,telefon,referral,sosyal_medya,diger'],
            'message' => ['nullable', 'string', 'max:5000'],
            'requested_site_visit_date' => ['nullable', 'date', 'after:today'],
            
            'kvkk_consent' => ['required', 'accepted'], // KVKK onayı zorunlu
            
            // Anti-spam
            'captcha_answer' => ['required', 'integer'], // Matematik sorusu cevabı
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ad Soyad / Firma adı gereklidir.',
            'phone.required' => 'Telefon numarası gereklidir.',
            'phone.regex' => 'Geçerli bir Türkiye telefon numarası giriniz. (örn: 0532 123 45 67)',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'kvkk_consent.required' => 'KVKK onayı gereklidir.',
            'kvkk_consent.accepted' => 'KVKK sözleşmesini kabul etmelisiniz.',
            'captcha_answer.required' => 'Güvenlik sorusunu cevaplayınız.',
            'requested_site_visit_date.after' => 'Keşif tarihi bugünden sonra olmalıdır.',
        ];
    }
}