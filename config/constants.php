<?php

return [
    'tenant_roles' => [
        'admin' => env('TENANT_ADMIN_ROLE_ID', 1), // Default to 1 if .env variable is not set
        'user' => env('TENANT_USER_ROLE_ID', 2),   // Default to 2 if .env variable is not set
        // other roles...
    ],
    // other constants...
];
