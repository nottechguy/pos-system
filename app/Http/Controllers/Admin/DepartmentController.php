<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Business;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('responsibleUser')->paginate(20);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.departments.create', compact('users'));
    }

    public function store(StoreDepartmentRequest $request) {
        $validated = $request->validated();

        $business = Business::first();
        $validated['business_id'] = $business->id;
        $validated['is_active'] = $request->has('is_active');

        Department::create($validated);

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Departamento creado correctamente.');
    }

    public function edit(Department $department)
    {
        $users = User::all();
        // Load the related categories to display in the related card
        $department->load('categories');
        
        return view('admin.departments.edit', compact('department', 'users'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department) {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');
        $department->update($validated);

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Departamento actualizado correctamente.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')
                         ->with('success', 'Departamento eliminado correctamente.');
    }
}