<?php

return [
    'super_admin_email' => env('SUPER_ADMIN_EMAIL', 'owiaba@owiaba.com'),
    'tenant_roles' => [
        'admin' => env('TENANT_ADMIN_ROLE_ID', 1), // Default to 1 if .env variable is not set
        'user' => env('TENANT_USER_ROLE_ID', 2),   // Default to 2 if .env variable is not set
        // other roles...
    ],
    'subscription_statuses' => [
        'active' => 1,
        'expired' => 2,
        'cancelled' => 3,
        // other statuses...
    ],
    // other constants...
];
