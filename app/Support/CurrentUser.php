<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CurrentUser
{
    public static function id(): ?int
    {
        return Auth::id() ?? User::query()->value('id');
    }
}
