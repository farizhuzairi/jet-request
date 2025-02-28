<?php

namespace Jet\Request\Client\Traits;

trait Keyable
{
    /**
     * Authorization Bearer
     * 
     */
    protected string $token;

    /**
     * Application request standards
     * EMS Platforms ...
     * 
     */
    protected string $appId;

    /**
     * License Key (User Client Key)
     * 
     */
    protected string $license;
}