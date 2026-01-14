<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    public function index(Request $request) {
        $userId = Auth::user()->id;
        $keywords = $request->get('keywords');
        $tools = Tool::with('category.toolsman')
        ->whereHas('category.toolsman', function ($query) use ($userId) {
            return $query->where('toolsman_id', $userId);
        })
        ->when($keywords, function ($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })
        ->get();

        return view('_toolsman.tool.index', compact('tools', 'keywords'));
    }

    public function add() {
        $userId = Auth::user()->id;
        $categories = Category::where('toolsman_id', $userId)->get();
        return view('_toolsman.tool.add', compact('categories'));
    }

    public function doCreate(Request $request) {
        $userId = Auth::user()->id;
        $category = Category::where('toolsman_id', $userId)->first();
        if (!$category) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan di kategori manapun.');
        }
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'quantity' => 'required|numeric',
        ]);

        $data = $request->all();
        $data['category_id'] = $category->id;
       
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tools', 'public');
        } else {
            $data['image'] = null;
        }

        $data['status'] = ($request->quantity >= 1) ? 'Tersedia' : 'Tidak Tersedia';

        Tool::create($data);

        return redirect()->route('toolsman.tools.index')->with('success', 'Tool created successfully');
    }

    
    public function update(int $id) {
        $userId = Auth::user()->id;
        $tool = Tool::whereHas('category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->findOrFail($id);
        $categories = Category::all();
        return view('_toolsman.tool.update', compact('tool', 'categories'));
    }
    
    public function doUpdate(int $id, Request $request) {

        $userId = Auth::user()->id;
        $category = Category::where('toolsman_id', $userId)->first();

        $tool = Tool::whereHas('category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'quantity' => 'required|numeric',
        ]);
        
        $data['category_id'] = $category->id;

        $data = $request->except('image');
        if ($request->hasFile('image')) {
            // Hapus file lama jika ada agar storage tidak penuh
            if ($tool->image) {
                Storage::disk('public')->delete($tool->image);
            }
            // Simpan file baru ke folder 'tools'
            $data['image'] = $request->file('image')->store('tools', 'public');
        } elseif ($request->remove_image == "1") {
            if ($tool->image) {
                Storage::disk('public')->delete($tool->image);
            }
            $data['image'] = null; // Set di DB jadi null
        }

        $data['status'] = ($request->quantity >= 1) ? 'Tersedia' : 'Tidak Tersedia';

        Tool::updateOrCreate([
            'id' => $id,
        ], $data);
        return redirect()->route('toolsman.tools.index')->with('success', 'Tool updated successfully');
    }
    
    public function delete(int $id) {
        $tool = Tool::findOrFail($id);
        if ($tool->image) {
            Storage::disk('public')->delete($tool->image);
        }
        $tool->delete($id);
        return redirect()->route('toolsman.tools.index')->with('success', 'Tool deleted successfully');
    }

}
