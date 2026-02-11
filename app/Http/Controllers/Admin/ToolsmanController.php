<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\StoreToolsmanRequest;
use App\Http\Requests\Admin\UpdateToolsmanRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ToolsmanController extends Controller
{
    protected array $page = [
        'route' => 'toolsman',
        'title' => 'Toolsman',
    ];

    protected string $baseRedirect;

    public function __construct()
    {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }

    public function index(Request $request): View|Response
    {
        $query = User::query()
            ->when($request->get('keywords'), function ($query, $keywords) {
                return $query->where('username', 'like', '%'.$keywords.'%');
            })
            ->where('access_type', 2)
            ->orderBy('created_at', 'desc');

        $data = $query->paginate(10)->withQueryString();

        return view('_admin.toolsman.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
            'access_type' => $request->get('access_type'),
        ]);
    }

    public function add(): View|Response
    {
        return view('_admin.toolsman.add', [
            'page' => $this->page,
        ]);
    }

    public function doCreate(StoreToolsmanRequest $request)
    {

        User::create([
            'username'      => $request->username,
            'email'         => $request->email,
            'class'         => "Toolsman",
            'access_type'   => 2,
            'password'      => bcrypt('default'),
            'is_active'     => 1,
            'created_by'    => auth()->id(),
        ]);

        return redirect()->route('admin.toolsmans.index')->with('success', 'User berhasil disimpan!');
    }

    public function detail(int $id): View|RedirectResponse|Response
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.toolsman.detail', [
            'data' => $user,
            'page' => $this->page,
        ]);
    }

    public function update(int $id): View|RedirectResponse|Response
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.toolsman.update', [
            'data' => $user,
            'userId' => $id,
            'page' => $this->page,
        ]);
    }

    public function doUpdate(int $id, UpdateToolsmanRequest $request): RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()
                ->back()
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'class' => "Toolsman",
            'access_type' => 2,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.toolsmans.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
    }

    public function delete(int $id): RedirectResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return redirect()
                ->route('admin.toolsmans.index')
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        // Check if user is used in categories
        $isUsed = \Illuminate\Support\Facades\DB::table('categories')
            ->where('toolsman_id', $id)
            ->exists();

        if ($isUsed) {
            return redirect()
                ->route('admin.toolsmans.index')
                ->with('error', ResponseConst::ERROR_MESSAGE_USER_USED);
        }

        $user->delete($id);

        return redirect()
            ->route('admin.toolsmans.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
    }

    public function resetPassword(int $id): RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()
                ->route('admin.toolsmans.index')
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $user->update([
            'password' => Hash::make('default'),
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.toolsmans.index')
            ->with('success', 'Password berhasil direset menjadi default');
    }

}
