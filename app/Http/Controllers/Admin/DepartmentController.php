<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::paginate(10);
        return view('_admin.department.index', compact('departments'));
    }

    public function add()
    {
        return view('_admin.department.add');
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department added successfully');
    }

    public function update($id)
    {
        $department = Department::findOrFail($id);
        return view('_admin.department.update', compact('department'));
    }

    public function doUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $department = Department::findOrFail($id);
        $department->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully');
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully');
    }

}
