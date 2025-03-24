<?php

namespace Jet\Request\Client\Supports;

use Exception;
use Illuminate\Support\Facades\Crypt;
use Jet\Request\Client\Http\Exception\JetRequestException;

class Config
{
    // /** @var \Jet\Request\Client\Supports\Config|null */
    // private static $instance;

    // /** @var string|null */
    // private $__CONFIG;

    // private function __construct(
    //     string $config
    // )
    // {
    //     $this->__CONFIG = $config;
    // }

    // public static function getInstance(): Config
    // {
    //     if (self::$instance === null) {
    //         self::$instance = new self(
    //             Crypt::encrypt(config('jet-request'))
    //         );
    //     }

    //     return self::$instance;
    // }

    // public function get(): array
    // {
    //     try {
    //         return Crypt::decrypt($this->__CONFIG);
    //     } catch (\Throwable $th) {
    //         report(new JetRequestException($th->getMessage()));
    //     }

    //     return [];
    // }

    // private function __clone()
    // {}

    // public function __wakeup()
    // {
    //     throw new Exception("Cannot unserialize " . __CLASS__);
    // }
}