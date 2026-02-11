<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Place;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class ToolController extends Controller
{
    public function index(Request $request) {
        $keywords = $request->get('keywords');
        $tools = Tool::with('category.toolsman', 'place', 'type')->when($keywords, function ($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })->paginate(10);

        return view('_admin.tool.index', compact('tools', 'keywords'));
    }

    public function add() {
        $categories = Category::all();
        $places = Place::all();
        $types = Type::all();
        return view('_admin.tool.add', compact('categories', 'places', 'types'));
    }

    public function doCreate(Request $request) {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'category_id' => 'required',
            'place_id' => 'required',   
            'type_id' => 'required',
            'quantity' => 'required|numeric',
            'fine' => 'required|numeric',
        ]);

        $data = $request->all();
       
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tools', 'public');
        } else {
            $data['image'] = null;
        }

        $data['status'] = ($request->quantity >= 1) ? 'Tersedia' : 'Tidak Tersedia';

        Tool::create($data);

        return redirect()->route('admin.tools.index')->with('success', 'Tool created successfully');
    }

    
    public function update(int $id) {
        $tool = Tool::findOrFail($id);
        $categories = Category::all();
        $places = Place::all();
        $types = Type::all();
        return view('_admin.tool.update', compact('tool', 'categories', 'places', 'types'));
    }
    
    public function doUpdate(int $id, Request $request) {

        $tool = Tool::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'category_id' => 'required',
            'place_id' => 'required',
            'type_id' => 'required',
            'quantity' => 'required|numeric',
            'fine' => 'required|numeric',
        ]);
        
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
        return redirect()->route('admin.tools.index')->with('success', 'Tool updated successfully');
    }
    
    public function delete(int $id) {
        $tool = Tool::findOrFail($id);
        if ($tool->image) {
            Storage::disk('public')->delete($tool->image);
        }
        $tool->delete($id);
        return redirect()->route('admin.tools.index')->with('success', 'Tool deleted successfully');
    }

    public function generateQR($id)
    {
        $tool = Tool::findOrFail($id);
        $qrContent = "SMKN-TOOLS-ID:". $tool->id;
        
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->margin(0)->errorCorrection('H')->generate($qrContent));

        $data = [
            'data' => $tool,
            'qrcode' => $qrcode
        ];

        $customPaper = [0, 0, 300, 300];

        $pdf = Pdf::loadView('_admin.tool.generate-qr', $data)->setPaper($customPaper);

        return $pdf->download('Kode QR ' . $tool->name . '.pdf');
    }

}
