<?php

namespace App\Http\Controllers;

use App\Events\UserVerifiedEvent;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function verify(Request $request, UserService $userService)
    {
        $token = $request->get('token');

        if (!$token) {
            throw new NotFoundHttpException();
        }

        $user = $userService->findByToken($token);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $userService->activate($user);

        return view('user.verified');
    }
}
