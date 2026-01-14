<?php

namespace App\Http\Controllers\Admin;

use App\Constants\UserConst;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Constants\ResponseConst;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->get('keywords');
        $categories = Category::with('toolsman')->when($keywords, function ($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })->get();

        return view('_admin.category.index', compact('categories', 'keywords'));
    }

    public function add()
    {
        $toolsmen = User::where('access_type', UserConst::TOOLSMAN)
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)
                    ->from('categories')
                    ->whereColumn('categories.toolsman_id', 'users.id');
            })->get();

        return view('_admin.category.add', compact('toolsmen'));
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'toolsman_id' => 'required|exists:users,id|unique:categories,toolsman_id',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function update($id)
    {
        $category = Category::findOrFail($id);
        $toolsmen = User::where('access_type', UserConst::TOOLSMAN)
            ->where(function ($query) use ($category) {
                $query->whereNotExists(function ($q) {
                    $q->selectRaw(1)
                        ->from('categories')
                        ->whereColumn('categories.toolsman_id', 'users.id');
                })->orWhere('id', $category->toolsman_id);
            })->get();

        return view('_admin.category.update', compact('category', 'toolsmen'));
    }

    public function doUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'toolsman_id' => 'required|exists:users,id|unique:categories,toolsman_id,'.$id,
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        $isUsed = \Illuminate\Support\Facades\DB::table('tools')
            ->where('category_id', $id)
            ->exists();

        if ($isUsed) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', ResponseConst::ERROR_MESSAGE_USER_USED);
        }
        $category->delete($id);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
