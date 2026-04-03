<?php

declare(strict_types=1);

namespace Modules\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactAdmin extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var array<string, string>
     */
    public array $details;

    /**
     * Create a new message instance.
     *
     */
    /**
     * @param  array<string, string>  $details
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        $fromEmail = $this->details['email'] ?? null;

        if (! is_string($fromEmail) || $fromEmail === '') {
            $configuredFrom = config('mail.from.address');
            $fromEmail = is_string($configuredFrom) && $configuredFrom !== '' ? $configuredFrom : 'noreply@example.com';
        }

        return $this->from($fromEmail)->markdown('notification::emails.contact-admin');
    }
}
