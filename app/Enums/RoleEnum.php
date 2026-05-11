<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'Admin';
    case Customer = 'Customer';
    case TattooArtist = 'TattooArtist';
    case Studio = 'Studio';
}
