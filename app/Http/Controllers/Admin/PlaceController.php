<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->get('keywords');
        $places = Place::when($keywords, function ($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })->paginate(10)->withQueryString();
        return view('_admin.place.index', compact('places', 'keywords'));
    }

    public function add()
    {
        return view('_admin.place.add');
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Place::create($request->all());

        return redirect()->route('admin.places.index')->with('success', 'Place created successfully');
    }

    public function update($id)
    {
        $place = Place::findOrFail($id);

        return view('_admin.place.update', compact('place'));
    }

    public function doUpdate(Request $request, $id)
    {
        $place = Place::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $place->update($request->all());

        return redirect()->route('admin.places.index')->with('success', 'Place updated successfully');
    }

    public function delete($id)
    {
        $place = Place::findOrFail($id);
        $place->delete();

        return redirect()->route('admin.places.index')->with('success', 'Place deleted successfully');
    }
}
