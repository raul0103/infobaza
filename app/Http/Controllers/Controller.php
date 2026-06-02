<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function ensureOwnedByCurrentUser(Model $model): void
    {
        if ((int) $model->getAttribute('user_id') !== (int) Auth::id()) {
            abort(404);
        }
    }
}
