<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'regex:/^(\+90|0)?[5][0-9]{9}$/'],
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
            'budget_range' => ['nullable', 'string', 'max:255'],
            
            'source' => ['nullable', 'in:web,telefon,referral,sosyal_medya,diger'],
            'message' => ['nullable', 'string', 'max:5000'],
            'requested_site_visit_date' => ['nullable', 'date'],
            
            'status' => ['sometimes', 'required', 'in:new,contacted,site_visit_planned,quoted,won,lost'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:5000'],
            
            'contacted_at' => ['nullable', 'date'],
            'site_visit_at' => ['nullable', 'date'],
            'quoted_at' => ['nullable', 'date'],
        ];
    }
}