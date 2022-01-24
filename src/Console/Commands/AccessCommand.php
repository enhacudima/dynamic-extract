<?php

namespace Enhacudima\DynamicExtract\Console\Commands;

use Illuminate\Console\Command;
use Enhacudima\DynamicExtract\DataBase\Model\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-extract:access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure DynamicExtract user access';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  \App\Support\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        $this->info('Add new access');
        $email = $this->ask('What is access email?');
        $name = $this->ask('What is access name?');
        $time = $this->ask('What is access live time in days?');

    	$input=['email' => $email, 'name' => $name, 'time' => $time];
        $data = new Request();
        $data->setMethod('POST');
        $data->request->add($input);

        $validatorData = Validator::make($data->all(), [
            'email' => 'required|email',
            'name' => 'required|string|max:191',
            'time' => 'required|numeric|min:1',
        ],
        [

        ]
    	);
        if ($validatorData->fails()) {
            $variable = $validatorData->errors()->all();
            foreach ($variable as $key => $value) {
                $this->error($value);
            }
        }elseif ($this->confirm('Do you wish to give access to '.$email.'?')) {
            
            $expire_at = now()->addDays($time);
            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'expire_at' => $expire_at, 'password' => Hash::make(Str::random())]
            );
            // create a signed URL for login
            $url = URL::temporarySignedRoute(
                config('dynamic-extract.prefix').'/sign-in',
                $expire_at,
                ['user' => $user->id]
            );

            User::where('id',$user->id)
                ->update(['access_link' =>$url]);


            $this->info('Copy access link: '. $url);
        }
        return Command::SUCCESS;
    }
}
