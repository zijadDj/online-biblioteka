<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MakeLibrarian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run with: php artisan librarian:create
     */
    protected $signature = 'user:make-librarian';

    /**
     * The console command description.
     */
    protected $description = 'Create the first librarian for the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Enter your first name:');
        $surname = $this->ask('Enter your surname:');
        $email = $this->ask('Enter your email address:');
        $jmbg = $this->ask('Enter your JMBG:');


        $validator = Validator::make([
            'name'    => $name,
            'surname' => $surname,
            'email'   => $email,
            'jmbg'    => $jmbg,
        ], [
            'name'    => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email',
            'jmbg'    => 'required|digits:13|unique:users,jmbg',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $message) {
                $this->line(" - $message");
            }
            return 1;
        }


        $photoPath = 'default.jpg';


        $password = 'password';


        $user = User::create([
            'name'         => $name,
            'surname'      => $surname,
            'email'        => $email,
            'jmbg'         => $jmbg,
            'photo_path'   => $photoPath,
            'is_librarian' => '1',
            'password'     => Hash::make($password),
        ]);

        $this->info("Librarian {$user->name} {$user->surname} created successfully.");
        $this->info("Default password: {$password}");

        return 0;
    }
}
