<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

// @codingStandardsIgnoreStart
class VerifyCsrfToken extends Middleware
{
    // @codingStandardsIgnoreEnd
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
