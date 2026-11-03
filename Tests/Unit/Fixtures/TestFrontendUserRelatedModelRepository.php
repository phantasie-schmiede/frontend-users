<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\Fixtures;

use PSBits\FrontendUsers\Domain\Repository\AbstractFrontendUserRelatedModelRepository;

/**
 * Class TestFrontendUserRelatedModelRepository
 *
 * A concrete repository for testing AbstractFrontendUserRelatedModelRepository. The repository
 * derives the managed model from its own class name, so both class names must line up.
 *
 * @package PSBits\FrontendUsers\Tests\Unit\Fixtures
 */
class TestFrontendUserRelatedModelRepository extends AbstractFrontendUserRelatedModelRepository
{
}
