<?php

namespace App\Enums;

enum ContactType: string
{
    case Phone = 'phone';
    case Email = 'email';
    case Instagram = 'instagram';
    case WhatsApp = 'whatsapp';
    case Website = 'website';
    case Facebook = 'facebook';
    case Twitter = 'twitter';
    case TikTok = 'tiktok';

    /**
     * Get all valid values as an array.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get a validation rule string for use in Form Requests.
     */
    public static function validationRule(): string
    {
        return 'in:' . implode(',', self::values());
    }
}
