<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Show all staff
    public function index()
    {
        $staff = Staff::all();
        return view('admin.staff.index', [
            'heading' => 'Staff Management',
            'title'   => 'View Staff',
            'active'  => 'staff',
            'staff'   => $staff,
        ]);
    }

    // Show form to create new staff
    public function create()
    {
        return view('admin.staff.create', [
            'heading' => 'Staff Management',
            'title'   => 'Add New Staff',
            'active'  => 'staff',
        ]);
    }

    // Store staff
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'role'        => 'required|string|max:50',
            'email'       => 'nullable|email|unique:staff,email',
            'phone'       => ['required', 'regex:/^\d{11}$/'], // exactly 11 digits
            'salary'      => 'nullable|numeric',
            'experience'  => 'nullable|integer|min:0',
            'status'      => 'nullable|string',
            'joining_date' => 'nullable|date',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')->with('success', 'Staff added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);

        return view('admin.staff.edit', [
            'heading' => 'Staff Management',
            'title'   => 'Edit Staff',
            'active'  => 'staff',
            'staff'   => $staff,
        ]);
    }

    // Update staff
    public function update(Request $request)
    {
        $request->validate([
            'id'          => 'required|exists:staff,id',
            'name'        => 'required|string|max:100',
            'role'        => 'required|string|max:50',
            'email'       => 'nullable|email|unique:staff,email,' . $request->id,
            'phone'       => ['required', 'regex:/^\d{11}$/'], // exactly 11 digits
            'salary'      => 'nullable|numeric',
            'experience'  => 'nullable|integer|min:0',
            'status'      => 'nullable|string',
            'joining_date' => 'nullable|date',
        ]);

        $staff = Staff::findOrFail($request->id);
        $staff->update($request->all());

        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    // Delete staff
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully.');
    }
}
