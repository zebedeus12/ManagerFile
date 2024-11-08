<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::all();

        $query = Employee::query();

        if ($request->has('search')) {
            $query->where('nama_user', 'like', '%' . $request->search . '%')
                ->orWhere('login', 'like', '%' . $request->search . '%');
        }

        $employees = $query->get();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required',
            'login' => 'required|unique:tb_arsipuser_copy',
            'password' => 'required',
            'role' => 'required|in:super_admin,admin,user',
            'id_struktural_tim' => 'required|integer',

        ]);

        // Encrypt the password before storing
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nama_user' => 'required',
            'login' => 'required|unique:tb_arsipuser_copy,login,' . $employee->id_user . ',id_user',
            'role' => 'required|in:super_admin,admin,user', // Validasi role
        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            // Update the password if provided
            $data['password'] = Hash::make($request->password);
        } else {
            // Keep the old password if not provided
            $data['password'] = $employee->password;
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
