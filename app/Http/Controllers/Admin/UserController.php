<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            ->where('access_type', 3)
            ->orderBy('created_at', 'desc');

        $data = $query->paginate(10)->withQueryString();
        $departments = Department::all();

        return view('_admin.users.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
            'access_type' => $request->get('access_type'),
            'departments' => $departments,
        ]);
    }

    public function add(): View|Response
    {
        $departments = Department::all();
        return view('_admin.users.add', [
            'page' => $this->page,
            'departments' => $departments,
        ]);
    }

    public function doCreate(StoreUserRequest $request)
    {

        User::create([
            'username'      => $request->username,
            'email'         => $request->email,
            'department_id' => $request->department_id,
            'years_in'      => $request->years_in, 
            'class'         => null,
            'access_type'   => 3,
            'password'      => bcrypt('default'),
            'is_active'     => 1,
            'created_by'    => auth()->id(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil disimpan!');
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

        $departments = Department::all();

        return view('_admin.users.update', [
            'data' => $user,
            'userId' => $id,
            'page' => $this->page,
            'departments' => $departments,
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
            'email' => $request->email,
            'department_id' => $request->department_id,
            'years_in' => $request->years_in,
            'class' => null,
            'access_type' => 3,
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

        $isUsed = \Illuminate\Support\Facades\DB::table('categories')
            ->where('toolsman_id', $id)
            ->exists();

        if ($isUsed) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', ResponseConst::ERROR_MESSAGE_USER_USED);
        }

        $isHaveLoans = \Illuminate\Support\Facades\DB::table('loans')
            ->where('user_id', $id)
            ->whereIn('status', ['approve', 'returning'])
            ->exists();

        if ($isHaveLoans) {
            DB::transaction(function () use ($user) {
                $loans = $user->loans()->whereIn('status', ['approve', 'returning'])->get();
               
                foreach ($loans as $loan) {
                    $loan->tool()->increment('quantity', $loan->quantity);
                    
                    $loan->update([
                        'status' => 'returned', 
                        'return_date' => now(),
                        'keterangan_status' => 'Otomatis Kembali (User Dihapus)'
                    ]);
                }

                $user->delete();
            });
        } else {
            $user->delete($id);
        }


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
