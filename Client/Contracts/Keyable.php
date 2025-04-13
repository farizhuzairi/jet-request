<?php

namespace Jet\Request\Client\Contracts;

interface Keyable
{
    public function getToken(): string;
}