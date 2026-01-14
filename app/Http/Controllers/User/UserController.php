<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Admin\ChangePasswordRequest;

class UserController extends Controller
{

    public function changePassword(): View
    {
        return view('_user.profile.change_password');
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
