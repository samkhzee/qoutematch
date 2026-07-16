<?php

namespace App\Lib;

class MessageSanitizer
{
    /**
     * Mask contact details in chat until a quote is accepted (Blueprint §15).
     */
    public static function sanitize(?string $message, bool $revealContacts = false): ?string
    {
        if ($message === null || $message === '') {
            return $message;
        }

        if ($revealContacts) {
            return $message;
        }

        $sanitized = preg_replace(
            '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i',
            '[email hidden until quote accepted]',
            $message
        );

        $sanitized = preg_replace(
            '/(\+?\d[\d\s().-]{7,}\d)/',
            '[phone hidden until quote accepted]',
            $sanitized
        );

        $sanitized = preg_replace(
            '/\b(?:https?:\/\/|www\.)[^\s<]+/i',
            '[link hidden until quote accepted]',
            $sanitized
        );

        return $sanitized;
    }

    public static function shouldRevealContacts(?int $bidStatus): bool
    {
        return $bidStatus === \App\Constants\Status::BID_ACCEPTED;
    }
}
