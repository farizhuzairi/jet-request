<?php

namespace Jet\Request\Client\Traits;

use Illuminate\Support\Facades\Crypt;

trait Keyable
{
    /**
     * Authorization Bearer
     * 
     */
    protected ?string $token = null;

    /**
     * Application request standards
     * 
     */
    protected ?string $appId = null;

    /**
     * License Key (User Client Key)
     * 
     */
    protected ?string $license = null;

    /**
     * With Token, set Authorization Bearer
     * 
     */
    public function token(string $token): static
    {
        if(! empty($token)) {
            $token = "Bearer {$token}";
            $this->token = Crypt::encrypt($token);
        }

        return $this;
    }

    /**
     * Get Token
     * 
     */
    public function getToken(): ?string
    {
        if($this->hasToken()) {
            return Crypt::decrypt($this->token);
        }

        return null;
    }

    /**
     * Has Token
     * 
     */
    public function hasToken(): bool
    {
        if(! empty($this->token)) {
            return true;
        }

        return false;
    }

    /**
     * Get App ID
     * 
     */
    protected function getAppId(): ?string
    {
        return $this->appId;
    }

    /**
     * Get License
     * 
     */
    protected function getLicense(): ?string
    {
        return $this->license;
    }
}