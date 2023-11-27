<?php

namespace App\Librerias\Permissions;

use Silber\Bouncer\Database\Concerns\HasAbilities;

trait HasRolesAndAbilities
{
    use HasRoles, HasAbilities {
        HasRoles::getClipboardInstance insteadof HasAbilities;
    }
}
