<?php


namespace App\Services;


use App\Events\UserCreatedEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserService
{
    public function findByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }

    public function create(string $email): User
    {
        $verification_token = Str::random(10);
        $user = new User();
        $user->email = $email;
        $user->verification_token = $verification_token;
        $user->save();

        event(new UserCreatedEvent($user));
        return $user;
    }

    public function findByToken(string $token): User|null
    {
        return User::where('verification_token', $token)->where('is_active', 0)->first();
    }

    public function activate(User $user)
    {
        $user->is_active = true;
        $user->email_verified_at = Carbon::now()->format('Y-m-d h:m:s');
        $user->save();
    }
}
