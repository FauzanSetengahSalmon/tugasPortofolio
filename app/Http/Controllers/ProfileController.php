<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(ProfileRequest $request, Profile $profile)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $profile->update($data);
        return redirect()->route('home', ['edit' => 1])->with('success', 'Data diri berhasil diperbarui.');
    }
}