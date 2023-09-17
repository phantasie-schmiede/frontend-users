<?php
declare(strict_types=1);

use PSB\PsbFoundation\Service\GlobalVariableService;
use PSB\PsbFrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;

defined('TYPO3') or die();

(static function() {
    GlobalVariableService::registerGlobalVariableProvider(
        FrontendUserProvider::class
    );
})();
