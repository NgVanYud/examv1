<?php

namespace App\Listeners;

use App\Notifications\UserNeedsPasswordReset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(\App\Events\UserCreated $event)
    {
      $user = $event->user;
      if(!$user->hasRole(config('access.roles_list.student'))) {
        $user->notify(new UserNeedsPasswordReset($user->createPwdResetToken(), $user));
      }
    }
}
