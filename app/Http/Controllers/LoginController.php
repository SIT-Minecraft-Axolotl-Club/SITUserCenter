<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('eduroamlogin');
    }

    public function handleLogin(Request $request)
    {
        $studentNumber = $request->input('student_number');
        $password = $request->input('password');

        $emailForAuthentication = $studentNumber . '@sit.edu.cn';
        $emailForDatabase = $studentNumber . '@mail.sit.edu.cn';

        try {
            $client = Http::timeout(15)->withOptions([
                'allow_redirects' => true
            ]);

            $page = $client->get(
                'https://analysis.eduroam.edu.cn/checkc/pkudetection'
            );

            $cookies = [];

            foreach ($page->cookies() as $cookie) {
                $cookies[$cookie->getName()] = $cookie->getValue();
            }

            preg_match(
                '/name="csrf-token" content="([^"]+)"/',
                $page->body(),
                $matches
            );

            $csrf = $matches[1] ?? null;

            if (!$csrf) {
                return back()->withErrors([
                    'login' => '无法获取验证令牌。'
                ]);
            }

            $response = $client
                ->withCookies(
                    $cookies,
                    'analysis.eduroam.edu.cn'
                )
                ->asForm()
                ->post(
                    'https://analysis.eduroam.edu.cn/checkc/peapmschap',
                    [
                        'username' => $emailForAuthentication,
                        'passwd' => $password,
                        'sid' => mt_rand() / mt_getrandmax(),
                        '_csrf-f' => $csrf
                    ]
                );

        } catch (\Exception $e) {
            return back()->withErrors([
                'login' => '认证服务异常。'
            ]);
        }

        $body = $response->body();

        if (stripos($body, 'FAILURE') !== false) {
            return back()->withErrors([
                'login' => '用户名或密码错误。'
            ]);
        }

        if (stripos($body, 'SUCCESS') !== false) {

            $user = User::where(
                'email',
                $emailForDatabase
            )->first();

            if (!$user) {
                $user = new User();

                $user->email = $emailForDatabase;
                $user->score = 1000;
                $user->avatar = 0;
                $user->verified = 1;
                $user->password = Hash::make($password);
                $user->ip = $request->ip();
                $user->permission = User::NORMAL;
                $user->register_at = Carbon::now();
                $user->last_sign_at = Carbon::now()->subDay();

                $user->save();

                Auth::login($user, true);

                return redirect(
                    $request->session()->pull(
                        'last_requested_path',
                        url('/user')
                    )
                )->with(
                    'status',
                    '注册成功并已登录。'
                );
            }

            Auth::login($user, true);

            return redirect(
                $request->session()->pull(
                    'last_requested_path',
                    url('/user')
                )
            )->with(
                'status',
                '登录成功。'
            );
        }

        return back()->withErrors([
            'login' => '返回未知结果。'
        ]);
    }
}