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
            'foundation' => '4.2',
            'php'        => '8.3.0-8.5.99',
            'typo3'      => '13.4.0-14.99.99',
        ],
        'suggests'  => [],
    ],
    'description'      => 'a versatile set of tools and objects for dealing with frontend-users',
    'state'            => 'stable',
    'title'            => 'PSbits | Frontend users',
    'version'          => '2.0.0',
];
