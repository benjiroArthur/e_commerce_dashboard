<?php

namespace App\Jobs;

use App\Mail\NewUserMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewUserNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        //initialize data from constructor
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = [
            'user' => $this->data['user'],
            'message' => $this->data['message'],
            'request_title' => 'New User',
            'item_url' => env('APP_URL'),
            'item_text' => 'Login',
            'subject' => 'New User Registration'
        ];
        Mail::to($this->data['user']->email)->send(new NewUserMail($message));
    }
}
