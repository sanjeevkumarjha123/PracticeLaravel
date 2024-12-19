<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendUserCreatedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $model;
    protected $password;

    /**
     * Create a new job instance.
     */
    public function __construct($model,$password)
    {
        $this->model = $model;
        $this->password = $password;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = $this->model;
        $password = $this->password;

        // Mail::raw("Hello, {$user->fullname}\n\nThank you for joining our platform.", function ($message) use ($user) {
        //     $message->to($user->email)
        //             ->subject('Welcome to Our Platform');
        // });
        Mail::send('emails.user_created', ['model' => $model,'password' => $password], function ($message) use ($model) {
            $message->to($model->email)
                    ->subject('Welcome to Our Platform');
        });


    }
}
