<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use App\Models\RespuestaSeguridad;
use Illuminate\Support\Facades\Log;

class ResetSecurityQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-security {email : The email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset security questions for a user by email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Find user
        $user = Usuario::where('correo', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }
        
        $this->info("Found user: {$user->correo} (ID: {$user->id})");
        
        // Check existing security questions
        $existingQuestions = RespuestaSeguridad::where('user_id', $user->id)->count();
        
        if ($existingQuestions === 0) {
            $this->warn("User has no security questions set.");
            return 0;
        }
        
        $this->info("User currently has {$existingQuestions} security question(s).");
        
        if ($this->confirm('Are you sure you want to delete all security questions for this user?', false)) {
            try {
                RespuestaSeguridad::where('user_id', $user->id)->delete();
                
                Log::info("Security questions reset for user", [
                    'user_id' => $user->id,
                    'email' => $user->correo,
                    'admin' => auth()->user()->correo ?? 'CLI'
                ]);
                
                $this->info("✓ Successfully deleted {$existingQuestions} security question(s).");
                $this->info("User can now set new security questions from their profile after logging in.");
                
                return 0;
            } catch (\Exception $e) {
                $this->error("Error resetting security questions: " . $e->getMessage());
                Log::error("Error resetting security questions", [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                return 1;
            }
        } else {
            $this->info("Operation cancelled.");
            return 0;
        }
    }
}
