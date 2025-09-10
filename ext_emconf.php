<?php
declare(strict_types=1);

$EM_CONF['psb_frontend_users'] = [
    'author'           => 'Daniel Ablass',
    'author_email'     => 'dn@phantasie-schmiede.de',
    'category'         => 'misc',
    'clearCacheOnLoad' => true,
    'constraints'      => [
        'conflicts' => [],
        'depends'   => [
            'php'            => '8.3',
            'psb_foundation' => '3.0',
            'typo3'          => '13.4.0-13.4.99',
        ],
        'suggests'  => [],
    ],
    'description'      => 'a versatile set of tools and objects for dealing with frontend-users',
    'state'            => 'stable',
    'title'            => 'PSbits | Frontend users',
    'version'          => '2.0.0',
];
