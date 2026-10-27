<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePosTerminalRequest;
use App\Http\Requests\UpdatePosTerminalRequest;
use App\Models\Store;
use App\Models\PosTerminal;
use Illuminate\Validation\Rule;

class PosTerminalController extends Controller
{
    public function index(Store $store)
    {
        $terminals = $store->terminals()->paginate(15);
        return view('admin.stores.terminals.index', compact('store', 'terminals'));
    }

    public function create(Store $store)
    {
        return view('admin.stores.terminals.create', compact('store'));
    }

    public function store(StorePosTerminalRequest $request, Store $store)
    {
        $validated = $request->validated();

        $validated['store_id'] = $store->id;
        $validated['is_active'] = $request->has('is_active');

        // device_uuid and app_version are typically populated automatically 
        // when the physical POS device connects to the API for the first time.

        PosTerminal::create($validated);

        return redirect()->route('admin.stores.terminals.index', $store->id)
                         ->with('success', __('messages.dashboard.pos_terminals.created_successfully'));
    }

    public function edit(Store $store, PosTerminal $terminal)
    {
        // Security check: ensure terminal belongs to this store
        if ($terminal->store_id !== $store->id) {
            abort(404);
        }

        return view('admin.stores.terminals.edit', compact('store', 'terminal'));
    }

    public function update(UpdatePosTerminalRequest $request, Store $store, PosTerminal $terminal) {
        if ($terminal->store_id !== $store->id) {
            abort(404);
        }

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');
        $terminal->update($validated);

        return redirect()->route('admin.stores.terminals.index', $store->id)
                         ->with('success', __('messages.dashboard.pos_terminals.updated_successfully'));
    }

    public function destroy(Store $store, PosTerminal $terminal)
    {
        if ($terminal->store_id === $store->id) {
            $terminal->delete();
            return redirect()->route('admin.stores.terminals.index', $store->id)
                             ->with('success', _('messages.dashboard.pos_terminals.deleted_successfully'));
        }
        
        abort(403);
    }
}