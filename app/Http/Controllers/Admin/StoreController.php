<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Business;
use App\Models\User;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('manager')->paginate(20);
        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        // Fetch users who could potentially be managers
        $users = User::all();
        return view('admin.stores.create', compact('users'));
    }

    public function store(StoreStoreRequest $request)
    {
        $validated = $request->validated();

        $business = Business::first();
        $validated['business_id'] = $business->id;
        $validated['is_active'] = $request->has('is_active');

        Store::create($validated);

        return redirect()->route('admin.stores.index')
                         ->with('success', __('messages.dashboard.stores.created_successfully'));
    }

    public function edit(Store $store) {
        $users = User::all();
        // Load relationships to display in the related cards
        $store->load(['users', 'terminals']);
        
        return view('admin.stores.edit', compact('store', 'users'));
    }

    public function update(UpdateStoreRequest $request, Store $store) {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active');
        $store->update($validated);

        return redirect()->route('admin.stores.index')
                         ->with('success', __('messages.dashboard.stores.updated_successfully'));
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')
                         ->with('success', __('messages.dashboard.stores.deleted_successfully'));
    }
}