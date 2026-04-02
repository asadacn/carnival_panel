<?php
require 'bootstrap/app.php';

$app->make(\Illuminate\Contracts\Http\Kernel::class)->handle(
    $request = \Illuminate\Http\Request::create('/ajax/clients/1/bills', 'GET')
)->send();
