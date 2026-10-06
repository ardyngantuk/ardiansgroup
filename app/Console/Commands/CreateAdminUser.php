<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {email} {--name=Administrator}';
    protected $description = 'Membuat atau memperbarui akun admin toko';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = $this->secret('Password admin');

        if (!$password) {
            $this->error('Password wajib diisi.');
            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $this->option('name');
        $user->password = $password;
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        $this->info("Akun admin {$email} siap digunakan.");
        return self::SUCCESS;
    }
}