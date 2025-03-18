<?php

namespace Jet\Request\Client\Factory;

use Hascamp\BaseCrypt\Encryption\BaseCrypt;
use Jet\Request\Client\Contracts\Keyable;

class Token implements Keyable
{
    private const TOKEN = "tokenable_key";
    private static $storages = [
        "session",
        "environment",
    ];

    protected static ?string $token = null;
    protected static string $storage;
    protected static ?string $encryption;

    /**
     * Set token storage to: session | environment
     * 
     */
    public function __construct(
        ?string $storage = null,
        ?string $crypted = null,
    )
    {
        if(empty($storage)) $storage = "session";
        $this->set_storage_path($storage, $crypted);
    }

    protected function set_storage_path(string $storage, ?string $crypted): void
    {
        if(in_array($storage, static::$storages)) {

            static::$storage = $storage;
            if(! empty($crypted)) static::$encryption = $crypted;

        }
    }

    protected function is_token_by_session(): bool
    {
        return static::$storage === "session";
    }

    protected function is_token_by_environment(): bool
    {
        return static::$storage === "environment";
    }

    protected function set_token_by_session(string $token): void
    {
        if($this->is_token_by_session()) {
            $crypt = static::$encryption;
    
            if($crypt) {
                $crypt = BaseCrypt::code($crypt, $token);
            }
            
            session([static::TOKEN => $token]);
        }
    }

    public function hasTokenBySession(): bool
    {
        return session()->has(static::TOKEN);
    }

    public function setToken(string $token): static
    {
        $this->set_token_by_session($token);
        return $this;
    }

    public function getToken(): string
    {
        if($this->is_token_by_session()) {

            if($this->hasTokenBySession()) {

                $token = session(static::TOKEN);
                $crypt = static::$encryption;

                if($crypt) {
                    return BaseCrypt::code($crypt, $token, 'decrypt');
                }

                return $token;
            }

        }

        if($this->is_token_by_environment()) {

            return config('jet-request.token');

        }

        return "";
    }
}