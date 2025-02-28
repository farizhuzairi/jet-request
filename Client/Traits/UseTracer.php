<?php

namespace Jet\Request\Client\Traits;

trait UseTracer
{
    /**
     * ID Permintaan yang dienkripsi
     * 
     */
    protected string $requestId;

    /**
     * Pemenuhan data sebagai catatan permintaan pengguna
     * yang dienkripsi dalam proses tracer
     * 
     * Ini akan berfungsi jika digunakan bersamaan dengan paket Direction Service
     */
    protected string $logs;
}