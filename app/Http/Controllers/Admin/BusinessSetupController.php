<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreBusinessSetupRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;

class BusinessSetupController extends Controller {
    public function create()
    {
        // Prevent accessing setup if a business is already configured
        if (Business::exists()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.setup.business');
    }

    public function store(StoreBusinessSetupRequest $request)
    {
        // Validating against the actual fields in the Business model
        $validated = $request->validated();
        Business::create($validated);

        return response()->json([
            'message' => 'Información del negocio guardada correctamente.',
            'redirect_url' => route('admin.dashboard')
        ]);
    }
}