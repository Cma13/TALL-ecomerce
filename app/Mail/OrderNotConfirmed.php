<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $order, $orderContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Order $order)
    {
        $isPayed = Order::where(['user_id' => $user->id])->first();

        if($isPayed->status == 1) {
            $this->user = $user;
            $this->order = $order;
            $content = Order::select('content')->where(['user_id' => $user->id]);
            $this->orderContent = json_decode($content, true);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('carlos@admin.com', 'Carlos')
            ->view('emails.order-not-confirmed');
    }
}
