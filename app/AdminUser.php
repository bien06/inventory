<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\UserAdminResetPasswordNotification;

class AdminUser extends User
{
    //
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserAdminResetPasswordNotification($token));
    }
}
