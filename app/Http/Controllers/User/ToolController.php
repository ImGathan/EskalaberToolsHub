<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;


class ToolController extends Controller
{
    
    public function index(Request $request) {
        $keywords = $request->get('keywords');
        $tools = Tool::with('category.toolsman')->when($keywords, function ($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })->paginate(20);

        return view('_user.tool.index', compact('tools', 'keywords'));
    }
}
