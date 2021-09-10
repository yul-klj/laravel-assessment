<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

// @codingStandardsIgnoreStart
class PreventRequestsDuringMaintenance extends Middleware
{
    // @codingStandardsIgnoreEnd
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
