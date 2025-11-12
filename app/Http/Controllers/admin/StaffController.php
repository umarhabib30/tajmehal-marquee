<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    // Show all staff
    public function index()
    {
        $staff = Staff::all();

        $data = [
            'heading' => 'Staff Management',
            'title'   => 'View Staff',
            'active'  => 'staff',
            'staff'   => $staff,
        ];

        return view('admin.staff.index', $data);
    }

    // Show form to create new staff
    public function create()
    {
        $data = [
            'heading' => 'Staff Management',
            'title'   => 'Add New Staff',
            'active'  => 'staff',
        ];

        return view('admin.staff.create', $data);
    }

    // Store staff
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:100',
            'role'         => 'required|string|max:50',
            'email'        => 'nullable|email',
            'phone'        => ['required', 'regex:/^\d{11}$/'],
            'idcardnumber' => ['regex:/^\d{5}-\d{7}-\d{1}$/'],
            'salary'       => 'nullable|numeric',
            'experience'   => 'nullable|integer|min:0',
            'status'       => 'nullable|string',
            'joining_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Invalid input or email/id card already taken.');
        }

        Staff::create($request->all());

        return redirect()->back()->with('success', 'Staff added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);

        $data = [
            'heading' => 'Staff Management',
            'title'   => 'Edit Staff',
            'active'  => 'staff',
            'staff'   => $staff,
        ];

        return view('admin.staff.edit', $data);
    }

    // Update staff
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'           => 'required|exists:staff,id',
            'name'         => 'required|string|max:100',
            'role'         => 'required|string|max:50',
            'email'        => 'nullable|email',
            'phone'        => ['required', 'regex:/^\d{11}$/'],
            'idcardnumber' => ['required', 'regex:/^\d{5}-\d{7}-\d{1}$/', 'unique:staff,idcardnumber,' . $request->id],
            'salary'       => 'nullable|numeric',
            'experience'   => 'nullable|integer|min:0',
            'status'       => 'nullable|string',
            'joining_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Invalid input or email/id card already taken.');
        }

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
