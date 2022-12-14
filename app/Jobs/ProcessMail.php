<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user=$user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rand=rand(130290,999999);
        $response = Http::withBasicAuth('3c0df5a8','jHc3YYviRsjaBoel')->post(
            "https://rest.nexmo.com/sms/json",
        [
            'from'=> 'BabyCrib Inc',
            'to' => "52".$this->user->tel,
            "text" => "Codigo de verificacion: ".$rand." Ingreselo en la aplicacion"
        ]);
        $this->user->code_verf = $rand;
        $this->user->save();
    }
}
