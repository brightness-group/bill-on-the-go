<?php

namespace App\Listeners;

use App\Events\TeamviewerDataRetrievalProcessed;
use App\Models\Tenant\User;
use App\Notifications\TVImportNotifyUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTVDataSyncCompleteNotification
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
     * @param  \App\Events\TeamviewerDataRetrievalProcessed  $event
     * @return void
     */
    public function handle(TeamviewerDataRetrievalProcessed $event)
    {
        $user = User::where('email',$event->email)->first();

        $user->notify(new TVImportNotifyUser($event->tenant, $user, config('app.asset_url')));
    }
}
