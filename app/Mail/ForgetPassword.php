<?php

declare(strict_types=1);

namespace Modules\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public string $token) {}

    public function build(): self
    {
        return $this->markdown('notification::emails.forget-password');
    }
}
