<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => $this->passwordRules(),
        ])->validate();

        $avatar = null;
        if (isset($input['avatar']) && $input['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            $avatar = $input['avatar']->store('avatars', 'public');
        }

        return User::create([
            'name' => $input['name'],
            'title' => $input['title'] ?? null,
            'phone' => $input['phone'] ?? null,
            'avatar' => $avatar,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
