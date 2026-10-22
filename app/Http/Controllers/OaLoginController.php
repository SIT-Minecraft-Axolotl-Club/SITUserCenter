<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OaLoginController extends Controller
{
    protected $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
    protected $charsLen;

    public function __construct()
    {
        $this->charsLen = strlen($this->chars);
    }

    public function showLoginForm(Request $request)
    {
        $captchaUrl = route('oa.getCaptcha') . '?t=' . time();
        return view('oalogin', ['captcha' => $captchaUrl]);
    }

    public function getCaptcha(Request $request)
    {
        $sessionId  = session()->getId();
        $cookieFile = storage_path("app/temp_cookie_{$sessionId}.txt");
        session(['oa_cookie_file' => $cookieFile]);

        $ch = curl_init('https://authserver.sit.edu.cn/authserver/captcha.html');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR    => $cookieFile,
            CURLOPT_COOKIEFILE   => $cookieFile,
            CURLOPT_TIMEOUT      => 10,
        ]);
        $img = curl_exec($ch);

        return response($img)->header('Content-Type', 'image/jpeg');
    }

    public function handleLogin(Request $request)
    {
        $request->validate([
            'student_number' => 'required|string',
            'password'       => 'required|string',
            'captcha'        => 'required|string',
        ]);

        $stu   = $request->input('student_number');
        $pwd   = $request->input('password');
        $cap   = $request->input('captcha');
        $email = "{$stu}@mail.sit.edu.cn";
        $cookieFile = session('oa_cookie_file');

        if (! $cookieFile || ! file_exists($cookieFile)) {
            return back()->withErrors(['login' => '验证码已过期，请刷新后重试']);
        }

        $ok = $this->attemptOALogin($stu, $pwd, $cap, $cookieFile);
        if (! $ok) {
            return back()->withErrors(['login' => 'OA 登录失败，请检查学号、密码或验证码']);
        }

        $user = User::firstOrNew(['email' => $email]);
        if (! $user->exists) {
            $user->score         = 1000;
            $user->avatar        = 0;
            $user->verified      = 1;
            $user->password      = Hash::make($pwd);
            $user->ip            = $request->ip();
            $user->permission    = User::NORMAL;
            $user->register_at   = Carbon::now();
            $user->last_sign_at  = Carbon::now()->subDay();
            $user->save();
        }

        Auth::login($user, true);

        return redirect($request->session()->pull('last_requested_path', url('/user')))
               ->with('status', '登录成功');
    }

    protected function attemptOALogin($username, $password, $captcha, $cookieFile)
    {
        $loginUrl = 'https://authserver.sit.edu.cn/authserver/login';

        $ch = curl_init($loginUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE     => $cookieFile,
            CURLOPT_COOKIEJAR      => $cookieFile,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_FOLLOWLOCATION => false,
        ]);
        $html = curl_exec($ch);

        if (! preg_match('/name="execution" value="(.*?)"/', $html, $m1)) {
            return false;
        }
        if (! preg_match('/id="pwdDefaultEncryptSalt" value="(.*?)"/', $html, $m2)) {
            return false;
        }
        preg_match('/name="lt" value="(.*?)"/', $html, $m3);
        $execution = $m1[1];
        $salt      = $m2[1];
        $lt        = $m3[1] ?? '';

        $encrypted = $this->encryptAES($password, $salt);

        $post = [
            'username'       => $username,
            'password'       => $encrypted,
            'captchaResponse'=> $captcha,
            'execution'      => $execution,
            '_eventId'       => 'submit',
            'geolocation'    => '',
        ];
        if ($lt !== '') {
            $post['lt'] = $lt;
        }

        $ch = curl_init($loginUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($post),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEFILE     => $cookieFile,
            CURLOPT_COOKIEJAR      => $cookieFile,
            CURLOPT_TIMEOUT        => 10,
        ]);
        $resp = curl_exec($ch);

        if (stripos($resp, '安全退出') !== false || stripos($resp, 'auth_username') !== false) {
            return true;
        }
        if (stripos($resp, '验证码') !== false || stripos($resp, '密码错误') !== false || stripos($resp, 'login') !== false) {
            return false;
        }
        return false;
    }

    protected function encryptAES($data, $key)
    {
        $prefix = $this->randomStr(64);
        $plain  = $prefix . $data;

        $iv = str_repeat("\0", 16);

        $cipher = openssl_encrypt(
            $plain,
            'AES-128-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($cipher);
    }

    protected function randomStr($len)
    {
        $s = '';
        for ($i = 0; $i < $len; $i++) {
            $s .= $this->chars[random_int(0, $this->charsLen - 1)];
        }
        return $s;
    }
}
