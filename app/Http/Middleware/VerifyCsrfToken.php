<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from request forgery verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
