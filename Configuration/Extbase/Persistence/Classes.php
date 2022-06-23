<?php
declare(strict_types=1);

use PSB\PsbFrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\FrontendUserGroup;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\Page;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\TtContent;

return [
    FrontendUser::class      => [
        'tableName' => 'fe_users',
    ],
    FrontendUserGroup::class => [
        'tableName' => 'fe_groups',
    ],
    Page::class              => [
        'properties' => [
            'cType' => [
                'fieldName' => 'CType',
            ],
        ],
        'tableName'  => 'pages',
    ],
    TtContent::class         => [
        'properties' => [
            'cType' => [
                'fieldName' => 'CType',
            ],
        ],
        'tableName'  => 'tt_content',
    ],
];
