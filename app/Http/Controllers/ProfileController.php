<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->hasFile('signature_image')) {
            if ($user->signature) {
                Storage::disk('public')->delete($user->signature);
            }

            $user->signature = $request->file('signature_image')->store('signatures', 'public');
        }

        $user->save();

        return redirect()->route('user.profile');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function view() {
        return Inertia::render('Users/Index', [
            'user' => auth()->user()->load(['relationship', 'technology','occupation']),
            'showReunionLink' => $this->isReunionActive(),
        ]);
    }
    public function membership_form()
    {
        return Inertia::render('Users/ProfileEdit', [
            'user' => auth()->user(),
            'relationships' => \App\Models\Relationship::select('id','name')->get(),
            'occupations' => \App\Models\Occupation::select('id','name')->get(),
            'technologies' => \App\Models\Technology::select('id','name')->get(),
            'showReunionLink' => $this->isReunionActive(),
        ]);
    }

    public function update_password() {
        return Inertia::render('Users/UpdatePassword', [
            'user' => auth()->user(),
            'showReunionLink' => $this->isReunionActive(),
        ]);
    }

    public static function isReunionActive(): bool
    {
        $setting = SiteSetting::with('reunionPeriod')->first();

        if (
            !$setting ||
            !$setting->reunion ||
            !$setting->reunion_id ||
            !$setting->reunionPeriod
        ) {
            return false;
        }

        $reunion = $setting->reunionPeriod;

        $today = now()->toDateString();

        return $today >= $reunion->start_date
            && $today <= $reunion->end_date;
    }

    public function reunion()
    {
        $setting = SiteSetting::first();

        $payments = Payment::with('reunionPeriod','paymentMethod')->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $methods = PaymentMethod::where('data_status', 1)->whereNot('type', 'CASH')->get();

        return Inertia::render('Users/Reunion', [
            'payments' => $payments,
            'methods' => $methods,
            'showReunionLink' => $this->isReunionActive(),
        ]); 
    }


    public function reunionpayment(Request $request)
    {
        $setting = SiteSetting::first();

        // validation
        $data = $request->validate([
            'payment_date'   => 'required|date',
            'trx_id'         => 'required|string|max:100',
            'reference'      => 'nullable|string|max:255',
            'payment_method' => 'required',
        ]);

        // create
        Payment::create([
            'user_id' => auth()->id(),
            'reunion_period_id' => $setting->reunion_id,
            'payment_date' => $data['payment_date'],
            'trx_id' => $data['trx_id'],
            'reference' => $data['reference'],
            'payment_method' => $data['payment_method'],
            'payment_status' => 0,
        ]);
        return back()->with('success', 'Payment submitted successfully');
    }

    public function download($id)
    {
        $payment = payment::with(['user', 'reunionPeriod'])->findOrFail($id);

        $receiptModel = $payment->reunionPeriod->receipt_model;

        if ($receiptModel == 1) {
            return view('global', [
                'payment' => $payment
            ]);
        }

        return view('eid', [
            'payment' => $payment
        ]);
    }
    
}