<?php

Auth::guard('web')->logout();

$request->session()->invalidate();
$request->session()->regenerateToken();

return redirect('/');
