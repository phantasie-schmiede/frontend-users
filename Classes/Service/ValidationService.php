<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace PSB\PsbFrontendUsers\Service;

use Exception;
use JsonException;
use PSB\PsbFoundation\Service\GlobalVariableService;
use PSB\PsbFoundation\Service\LocalizationService;
use PSB\PsbFoundation\Utility\Configuration\FilePathUtility;
use PSB\PsbFoundation\Utility\ValidationUtility;
use PSB\PsbFrontendUsers\Data\ExtensionInformation;
use PSB\PsbFrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Class ObjectService
 *
 * @package PSB\PsbFrontendUsers\Service
 */
class ValidationService
{
    public const MODES = [
        'REDIRECT'        => 'redirect',
        'THROW_EXCEPTION' => 'throwException',
    ];

    /**
     * @param ExtensionInformation $extensionInformation
     * @param FlashMessageService  $flashMessageService
     * @param LocalizationService  $localizationService
     * @param UriBuilder           $uriBuilder
     */
    public function __construct(
        protected readonly ExtensionInformation $extensionInformation,
        protected readonly FlashMessageService  $flashMessageService,
        protected readonly LocalizationService  $localizationService,
        protected readonly UriBuilder           $uriBuilder,
    ) {
    }

    /**
     * @param string $mode
     *
     * @throws AspectNotFoundException
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws InvalidConfigurationTypeException
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws PropagateResponseException
     */
    public function requiresFrontendUser(string $mode = self::MODES['REDIRECT']): void
    {
        ValidationUtility::checkValueAgainstConstant(self::MODES, $mode);
        ValidationUtility::requiresFrontendContext();

        if (null === GlobalVariableService::get(FrontendUserProvider::class . '.user')) {
            switch ($mode) {
                case self::MODES['REDIRECT']:
                    $this->addFlashMessage();
                    throw new PropagateResponseException(
                        new RedirectResponse($this->buildRedirectUri(), 401), 1621982088
                    );
                case self::MODES['THROW_EXCEPTION']:
                    throw new RuntimeException(
                        __CLASS__ . ': This method requires a logged in frontend user!', 1621981688
                    );
            }
        }
    }

    /**
     * @return void
     * @throws AspectNotFoundException
     * @throws ContainerExceptionInterface
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws InvalidConfigurationTypeException
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    private function addFlashMessage(): void
    {
        $languageFilePath = FilePathUtility::getLanguageFilePathForCurrentFile($this->extensionInformation);
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $this->localizationService->translate($languageFilePath . 'flashMessage.redirect.bodytext'),
            $this->localizationService->translate($languageFilePath . 'flashMessage.redirect.header'),
            ContextualFeedbackSeverity::ERROR,
            true
        );
        $this->flashMessageService->getMessageQueueByIdentifier('psbFoundation.general')
            ->addMessage($message);
    }

    /**
     * @return string
     * @throws Exception
     */
    private function buildRedirectUri(): string
    {
        $loginPage = GlobalVariableService::get(FrontendUserProvider::class . '.loginPage');

        if (null === $loginPage) {
            $targetPageUid = $GLOBALS['TSFE']->rootLine[0]['uid'];
        } else {
            $targetPageUid = $loginPage->getUid();
        }

        return $this->uriBuilder->reset()
            ->setTargetPageUid($targetPageUid)
            ->buildFrontendUri();
    }
}
