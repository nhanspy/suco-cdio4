<?php

namespace App\Traits;

use App\Exceptions\OwnerForbiddenException;

trait ValidOwnerTrait
{
    public function isOwner($requestId, $currentId)
    {
        if ($requestId !== $currentId) {
            throw new OwnerForbiddenException();
        }

        return true;
    }
}
