<?php

return [
    'ldap' => [
        'enabled' => false,
        'account_suffix' => '@acme.org',
        'domain_controllers' => ['corp-dc1.corp.acme.org', 'corp-dc2.corp.acme.org'],
        'port' => 389,
        'base_dn' => 'dc=corp,dc=acme,dc=org',
        'admin_username' => 'username',
        'admin_password' => 'password',
        'follow_referrals' => true,
        'use_ssl' => false,
        'use_tls' => false,
        'use_sso' => false,
    ],
    'couchdb' => [
        'enabled' => false,
    ],
];
