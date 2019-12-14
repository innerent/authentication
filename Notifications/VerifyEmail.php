<?php

namespace Innerent\Authentication\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class VerifyEmail extends BaseVerifyEmail
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $prefix = trim(config('foundation.frontend.url'), '/') . '/' . trim(config('foundation.frontend.email_verify_url'), '/') . '/';

        $temporarySignedURL = URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(60), ['uuid' => $notifiable->uuid], false
        );

        $info = trim(Str::replaceFirst(env('INNERENT_API_PREFIX').'/auth/email/verify', '', $temporarySignedURL), '/');

        return $prefix . $info;
    }
}
