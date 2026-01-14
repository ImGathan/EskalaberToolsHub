<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Constants\TaskStatusConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\StoreTaskRequest;
use App\Http\Requests\Admin\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected array $page = [
        'route' => 'tasks',
        'title' => 'Manajemen Tugas',
    ];

    protected string $baseRedirect;

    public function __construct()
    {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }

    public function index(Request $request): View|Response
    {
        $query = Task::with('category')
            ->when($request->get('keywords'), function ($query, $keywords) {
                return $query->where('title', 'like', '%'.$keywords.'%');
            })
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->get('category_id'), function ($query, $categoryId) {
                return $query->where('task_category_id', $categoryId);
            })
            ->orderBy('created_at', 'desc');

        $data = $query->paginate(20)->withQueryString();

        $categories = TaskCategory::all();

        return view('_admin.tasks.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
            'status' => $request->get('status'),
            'category_id' => $request->get('category_id'),
            'categories' => $categories,
            'statuses' => TaskStatusConst::getList(),
        ]);
    }

    public function add(): View|Response
    {
        $categories = TaskCategory::all();

        return view('_admin.tasks.add', [
            'page' => $this->page,
            'categories' => $categories,
            'statuses' => TaskStatusConst::getList(),
        ]);
    }

    public function doCreate(StoreTaskRequest $request): RedirectResponse
    {
        Task::create([
            'title' => $request->title,
            'task_category_id' => $request->task_category_id,
            'task_date' => $request->task_date,
            'status' => $request->status,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
    }

    public function detail(int $id): View|RedirectResponse|Response
    {
        $task = Task::with('category')->find($id);

        if (! $task) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.tasks.detail', [
            'data' => $task,
            'id' => $id,
            'page' => $this->page,
        ]);
    }

    public function update(int $id): View|RedirectResponse|Response
    {
        $task = Task::find($id);

        if (! $task) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $categories = TaskCategory::all();

        return view('_admin.tasks.update', [
            'data' => $task,
            'id' => $id,
            'page' => $this->page,
            'categories' => $categories,
            'statuses' => TaskStatusConst::getList(),
        ]);
    }

    public function doUpdate(int $id, UpdateTaskRequest $request): RedirectResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return redirect()
                ->back()
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $task->update([
            'title' => $request->title,
            'task_category_id' => $request->task_category_id,
            'task_date' => $request->task_date,
            'status' => $request->status,
            'description' => $request->description,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
    }

    public function delete(int $id): RedirectResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return redirect()
                ->route('admin.tasks.index')
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $task->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
    }
}
