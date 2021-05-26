<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'store_signin',
        'store_code',
        'store_reset',
        'store_update',
        'store_update_password',
        'store_support',
        'update_status_shift',
        'update_asis_shift',
        'delete_user',
        'store_update_cliente',
        'store_create_cliente',
        'delete_noty_type',
        'delete_noty',
        'delete_noty_active',
        'store_shift',
        'reasing_shift',
        'set_business',
        'update_notifications_business',
        'update_status_business',
        'update_settings_business',
        'remove_mercado_pago',
        'update_reports_business',
        'update_works_business',
        'update_business',
        'create_business'
    ];
}
