<?php

namespace App\Livewire;

use App\Models\NewsletterSubscription;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public string $email = '';
    public string $message = '';
    public string $messageType = '';

    protected $rules = [
        'email' => 'required|email|max:255',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please provide a valid email address.',
    ];

    public function subscribe(): void
    {
        $this->validate();

        try {
            $existing = NewsletterSubscription::where('email', $this->email)->first();

            if ($existing) {
                if ($existing->is_active) {
                    $this->message = 'You are already subscribed to our newsletter!';
                    $this->messageType = 'error';
                } else {
                    $existing->resubscribe();
                    $this->message = 'Welcome back! Your subscription has been reactivated.';
                    $this->messageType = 'success';
                    $this->email = '';
                }
            } else {
                NewsletterSubscription::create([
                    'email' => $this->email,
                    'subscription_source' => 'website',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $this->message = "Thank you for subscribing! You'll receive our latest updates and insights.";
                $this->messageType = 'success';
                $this->email = '';
            }
        } catch (\Exception $e) {
            \Log::error('Newsletter subscription error: ' . $e->getMessage());
            $this->message = 'Something went wrong. Please try again later.';
            $this->messageType = 'error';
        }
    }

    public function clearMessage(): void
    {
        $this->message = '';
        $this->messageType = '';
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}
