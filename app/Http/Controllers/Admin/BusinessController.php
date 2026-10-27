<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller {
    public function edit() {
        // Assuming a single-tenant structure for the dashboard UI
        $business = Business::firstOrFail(); 
        $categories = BusinessCategory::all();

        return view('admin.business.edit', compact('business', 'categories'));
    }

    public function update(UpdateBusinessRequest $request, Business $business) {
        $validated = $request->validated();

        if ($request->hasFile('logo_path')) {
            // Delete old logo if exists
            if ($business->logo_path) {
                Storage::disk('public')->delete($business->logo_path);
            }
            $validated['logo_path'] = $request->file('logo_path')->store('business_logos', 'public');
        }

        $business->update($validated);

        return redirect()->route('admin.business.edit')
                         ->with('success', __('messages.dashboard.business.updated_successfully'));
    }
}