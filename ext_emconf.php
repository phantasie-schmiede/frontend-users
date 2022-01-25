<?php
declare(strict_types=1);

$EM_CONF['psb_frontend_users'] = [
    'author'           => 'Daniel Ablass',
    'author_email'     => 'dn@phantasie-schmiede.de',
    'category'         => 'misc',
    'clearCacheOnLoad' => true,
    'constraints'      => [
        'conflicts' => [
        ],
        'depends'   => [
            'php'            => '7.4',
            'psb_foundation' => '1.0',
        ],
        'suggests'  => [
        ],
    ],
    'description'      => 'a versatile set of tools and objects for dealing with frontend-users',
    'state'            => 'stable',
    'title'            => 'PSbits | Frontend users',
    'version'          => '1.0.0',
];
