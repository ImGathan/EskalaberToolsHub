<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected array $page = [
        'route' => 'user',
        'title' => 'Pengguna Aplikasi',
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
            ->when($request->get('access_type'), function ($query, $accessType) {
                if ($accessType !== 'all') {
                    return $query->where('access_type', $accessType);
                }
            })
            ->orderBy('created_at', 'desc');

        $data = $query->paginate(10)->withQueryString();

        return view('_admin.users.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
            'access_type' => $request->get('access_type'),
        ]);
    }

    public function add(): View|Response
    {
        return view('_admin.users.add', [
            'page' => $this->page,
        ]);
    }

    public function doCreate(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'username' => $request->username,
            'access_type' => $request->access_type,
            'class' => $request->class,
            'password' => Hash::make('default'),
            'is_active' => 1,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
    }

    public function detail(int $id): View|RedirectResponse|Response
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.users.detail', [
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

        return view('_admin.users.update', [
            'data' => $user,
            'userId' => $id,
            'page' => $this->page,
        ]);
    }

    public function doUpdate(int $id, UpdateUserRequest $request): RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()
                ->back()
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $user->update([
            'username' => $request->username,
            'class' => $request->class,
            'access_type' => $request->access_type,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
    }

    public function delete(int $id): RedirectResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        // Check if user is used in categories
        $isUsed = \Illuminate\Support\Facades\DB::table('categories')
            ->where('toolsman_id', $id)
            ->exists();

        if ($isUsed) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', ResponseConst::ERROR_MESSAGE_USER_USED);
        }

        $user->delete($id);

        return redirect()
            ->route('admin.users.index')
            ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
    }

    public function resetPassword(int $id): RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $user->update([
            'password' => Hash::make('default'),
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Password berhasil direset menjadi default');
    }

    public function changePassword(): View
    {
        return view('_admin.profile.change_password');
    }

    public function doChangePassword(ChangePasswordRequest $request): RedirectResponse
    {
        User::where('id', Auth::id())->update([
            'password' => Hash::make($request->password),
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Password berhasil diubah.');
    }
}
