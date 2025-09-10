<?php
declare(strict_types=1);

use PSBits\Foundation\Service\GlobalVariableService;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;

defined('TYPO3') or die();

(static function() {
    GlobalVariableService::registerGlobalVariableProvider(
        FrontendUserProvider::class
    );
})();
