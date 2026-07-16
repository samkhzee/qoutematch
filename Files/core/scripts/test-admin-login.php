<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$request = Request::create('/admin', 'POST', [
    'username' => 'admin@site.com',
    'password' => 'admin',
]);

$login = $request->input('username');
$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
$request->merge([$fieldType => $login]);

$credentials = $request->only($fieldType, 'password');
$ok = Auth::guard('admin')->attempt($credentials);

echo "Field used: {$fieldType}\n";
echo "Login with admin@site.com / admin: " . ($ok ? 'SUCCESS' : 'FAILED') . "\n";

Auth::guard('admin')->logout();

$request2 = Request::create('/admin', 'POST', [
    'username' => 'admin',
    'password' => 'admin',
]);
$login2 = $request2->input('username');
$fieldType2 = filter_var($login2, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
$request2->merge([$fieldType2 => $login2]);
$ok2 = Auth::guard('admin')->attempt($request2->only($fieldType2, 'password'));
echo "Login with admin / admin: " . ($ok2 ? 'SUCCESS' : 'FAILED') . "\n";
