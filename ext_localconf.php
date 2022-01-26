<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3_MODE') or die();

(static function () {
    \PSB\PsbFoundation\Service\GlobalVariableService::registerGlobalVariableProvider(\PSB\PsbFrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider::class);
})();
