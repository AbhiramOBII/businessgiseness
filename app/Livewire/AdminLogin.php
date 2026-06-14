<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminLogin extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    public string $errorMessage = '';
    public bool $isLoading = false;

    public function login(): void
    {
        $this->errorMessage = '';
        $this->validate();

        $throttleKey = Str::lower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = "Too many login attempts. Please try again in {$seconds} seconds.";
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey, 60);
            $this->errorMessage = 'Invalid email or password.';
            $this->password = '';
            return;
        }

        if (!Auth::user()->is_admin) {
            Auth::logout();
            $this->errorMessage = 'Access denied. This portal is for administrators only.';
            $this->password = '';
            return;
        }

        RateLimiter::clear($throttleKey);

        session()->regenerate();

        $this->redirect(route('admin.dashboard'), navigate: false);
    }

    public function render()
    {
        return view('livewire.admin-login');
    }
}
