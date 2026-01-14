<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TaskCategory\StoreTaskCategoryRequest;
use App\Http\Requests\Admin\TaskCategory\UpdateTaskCategoryRequest;
use App\Models\TaskCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TaskCategoryController extends Controller
{
    protected array $page = [
        'route' => 'task_categories',
        'title' => 'Kategori Tugas',
    ];

    protected string $baseRedirect;

    public function __construct()
    {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }

    public function index(Request $request): View|Response
    {
        $data = TaskCategory::query()
            ->when($request->get('keywords'), function ($query, $keywords) {
                return $query->where('name', 'like', '%'.$keywords.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('_admin.task_categories.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
        ]);
    }

    public function add(): View|Response
    {
        return view('_admin.task_categories.add', [
            'page' => $this->page,
        ]);
    }

    public function doCreate(StoreTaskCategoryRequest $request): RedirectResponse
    {
        TaskCategory::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.task_categories.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
    }

    public function update(int $id): View|RedirectResponse|Response
    {
        $category = TaskCategory::find($id);

        if (! $category) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.task_categories.update', [
            'data' => $category,
            'id' => $id,
            'page' => $this->page,
        ]);
    }

    public function doUpdate(int $id, UpdateTaskCategoryRequest $request): RedirectResponse
    {
        $category = TaskCategory::find($id);

        if (! $category) {
            return redirect()
                ->back()
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $category->update([
            'name' => $request->name,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.task_categories.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
    }

    public function delete(int $id): RedirectResponse
    {
        $category = TaskCategory::find($id);

        if (! $category) {
            return redirect()
                ->route('admin.task_categories.index')
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $category->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);

        return redirect()
            ->route('admin.task_categories.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
    }
}
