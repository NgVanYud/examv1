<?php

namespace App\Listeners;

use App\Notifications\UserNeedsPasswordReset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManagerCreated implements ShouldQueue
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
    public function handle(\App\Events\ManagerCreated $event)
    {
      $user = $event->user;
      $user->sendPasswordResetNotification($user->createPwdResetToken());
    }
}