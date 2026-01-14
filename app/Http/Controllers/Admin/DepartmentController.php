<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->get('keywords');
        $departments = Department::when($keywords, function ($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })->get();

        return view('_admin.department.index', compact('departments', 'keywords'));
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

        Department::create($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully');
    }

    public function update($id)
    {
        $department = Department::findOrFail($id);

        return view('_admin.department.update', compact('department'));
    }

    public function doUpdate(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $department->update($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully');
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully');
    }
}
