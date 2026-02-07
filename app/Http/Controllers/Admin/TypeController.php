<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::paginate(10);
        return view('_admin.type.index', compact('types'));
    }

    public function add()
    {
        return view('_admin.type.add');
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Type::create($request->all());

        return redirect()->route('admin.types.index')
            ->with('success', 'Type created successfully');
    }

    public function update($id)
    {
        $type = Type::find($id);
        return view('_admin.type.update', compact('type'));
    }

    public function doUpdate(Request $request, $id)
    {
        $type = Type::find($id);
        $request->validate([
            'name' => 'required',
        ]);

        $type->update($request->all());

        return redirect()->route('admin.types.index')
            ->with('success', 'Type updated successfully');
    }

    public function delete($id)
    {
        $type = Type::find($id);
        $type->delete();

        return redirect()->route('admin.types.index')
            ->with('success', 'Type deleted successfully');
    }
}
