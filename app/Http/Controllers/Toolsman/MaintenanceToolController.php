<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;

class MaintenanceToolController extends Controller
{
    public function index(Request $request) {
        $userId = Auth::user()->id;
        $keywords = $request->get('keywords');
        $tools = Tool::with('category.toolsman', 'place', 'type')
        ->whereHas('category.toolsman', function ($query) use ($userId) {
            return $query->where('toolsman_id', $userId);
        })
        ->when($keywords, function ($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })
        ->where('broken_qty', '>', 0)
        ->paginate(10);

        return view('_toolsman.maintenance-tool.index', compact('tools', 'keywords'));
    }

        // 1. Untuk Increment/Decrement jumlah rusak
    // public function updateQty(Request $request, $id) {
    //     $tool = Tool::findOrFail($id);
        
    //     if ($request->action === 'increment') {
    //         $tool->increment('broken_qty');
    //     } else {
    //         if ($tool->broken_qty > 0) {
    //             $tool->decrement('broken_qty');
    //         }
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'new_qty' => $tool->broken_qty
    //     ]);
    // }

    // 2. Untuk memindahkan semua broken_qty ke quantity (Gudang)
    public function restore(Request $request, $id) {
        $tool = Tool::findOrFail($id);
        
        // Ambil input dari form
        $qtyToRestore = (int) $request->qty_to_restore;

        // Validasi tambahan di sisi server (Security)
        if ($qtyToRestore > $tool->broken_qty) {
            return back()->with('error', "Jumlah melebihi barang rusak yang tersedia.");
        }

        if ($qtyToRestore > 0) {
            // Logika pindah stok
            $tool->quantity += $qtyToRestore;      
            $tool->broken_qty -= $qtyToRestore;    
            $tool->save();
            
            return back()->with('success', "Berhasil memulihkan $qtyToRestore unit barang.");
        }
        
        return back()->with('error', "Jumlah harus lebih dari 0.");
    }

}
