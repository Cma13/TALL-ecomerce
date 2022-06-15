<?php

namespace App\Listeners;

use App\Mail\OrderNotConfirmed;
use App\Models\Order;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class MailOrder
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
    public function handle(Logout $event)
    {
        $user = auth()->user();
        $order = Order::where(['user_id' => $user->id])->first();

        Mail::to($user, new OrderNotConfirmed($user, $order));
    }
}
