<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

// @codingStandardsIgnoreStart
class EncryptCookies extends Middleware
{
    // @codingStandardsIgnoreEnd
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
