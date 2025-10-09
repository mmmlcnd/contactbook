<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * 未認証ユーザーをどこにリダイレクトするか定義
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // URL で判断してリダイレクト先を変える
            if ($request->is('students/*')) {
                return route('login.student');
            }

            if ($request->is('teachers/*')) {
                return route('login.teacher');
            }

            if ($request->is('admins/*')) {
                return route('login.admin');
            }

            // 該当しない場合はデフォルトで生徒ログインへ
            return route('login.student');
        }

        return null;
    }
}
