<?php

namespace Jet\Request\Client;

use Jet\Request\Client\Factory\Token;
use Jet\Request\Client\Contracts\Keyable;

final class KeyService
{
    public static function token(?string $storage, ?string $crypted = null): Keyable
    {
        return new Token($storage, $crypted);
    }
}