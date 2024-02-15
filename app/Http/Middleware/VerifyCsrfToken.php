<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/api/security/login',
        'api/interestpoints/*',
        'api/pointcategories/*',
        'api/articlecategories/*',
        'api/articles/*',
        'api/users/*',
        'api/pointpictures/*',
        'api/pointcategories/*',
    ];
}
