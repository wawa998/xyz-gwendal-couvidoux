<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Show profile edit form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()
            ->load('codes')
            ->loadCount([
                'codes as remaining_codes_count' => fn ($query) => $query->remaining()
            ]);

        return view('app.profile', [
            'user' => $user,
            'codes' => $user->codes
        ]);
    }

    /**
     * Handle profile edit form.
     */
    public function update(Request $request, UserService $service): RedirectResponse
    {
        $request->validate([
            'avatar'   => ['nullable', 'image', 'max:1000'],
            'email'    => ['nullable', 'email'],
            'password' => ['nullable', 'string', 'min:4'],
        ]);

        $user = $request->user();

        if ($request->file('avatar') && $request->file('avatar')->isValid()) {
            $user->avatar = $service->setAvatar($request->file('avatar'));
        }

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        return redirect()->route('app.profile.edit');
    }
}
