<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreLeadRequest;
use App\Models\Lead;
use App\Services\LeadNumberGeneratorService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    protected $numberGenerator;

    public function __construct(LeadNumberGeneratorService $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Show the quote request form
     */
    public function create()
    {
        return view('public.quote-request');
    }

    /**
     * Store a new lead from public form
     */
    public function store(StoreLeadRequest $request)
    {
        // Generate lead number
        $leadNumber = $this->numberGenerator->generate();
        
        // Simple captcha validation
        $captchaNumber1 = $request->session()->get('captcha_number1');
        $captchaNumber2 = $request->session()->get('captcha_number2');
        $expectedAnswer = ($captchaNumber1 ?? 5) + ($captchaNumber2 ?? 3);
        
        if ($request->captcha_answer != $expectedAnswer) {
            return back()->withErrors(['captcha_answer' => 'Güvenlik sorusu yanlış.'])->withInput();
        }
        
        // Create lead
        $lead = Lead::create([
            'lead_number' => $leadNumber,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'company' => $request->company,
            'project_type' => $request->project_type,
            'location_city' => $request->location_city,
            'location_district' => $request->location_district,
            'location_address' => $request->location_address,
            'area_m2' => $request->area_m2,
            'room_count' => $request->room_count,
            'floor_count' => $request->floor_count,
            'current_status' => $request->current_status,
            'requested_services' => $request->requested_services,
            'budget_range' => $request->budget_range,
            'source' => 'web',
            'message' => $request->message,
            'requested_site_visit_date' => $request->requested_site_visit_date,
            'status' => 'new',
            'kvkk_consent' => true,
        ]);
        
        // Clear captcha from session
        $request->session()->forget(['captcha_number1', 'captcha_number2']);
        
        // TODO: Send notification email to admin
        // TODO: Send confirmation email/SMS to lead
        
        return redirect()->route('public.quote-request')
            ->with('success', 'Talebiniz başarıyla alındı! Talep numaranız: ' . $leadNumber . '. En kısa sürede sizinle iletişime geçeceğiz.');
    }
}