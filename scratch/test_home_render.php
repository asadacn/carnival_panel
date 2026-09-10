<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::first();
if ($user) {
    Auth::login($user);
    $request = Illuminate\Http\Request::create('/dashboard', 'GET');
    $request->setUserResolver(function () use ($user) {
        return $user;
    });
    // Create session and set user
    $session = $app->make('session')->driver();
    $session->put('_token', 'test');
    $session->put(Auth::getName(), $user->getAuthIdentifier());
    $request->setLaravelSession($session);

    $response = $kernel->handle($request);
    echo "Authenticated HTTP Response Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() === 200) {
        echo "Dashboard successfully rendered with all smooth charts!\n";
    }
} else {
    echo "No user found.\n";
}
