<?php

namespace App\Mail;

use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterUser extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $user = User::find(DB::table('users_activation'))
        // $token = DB::table('users_activation')->where('id_user',$user->id)->value('token');
        // return $token;
        // return $user;
        return $this->markdown('emails.register-user');
    }
}
