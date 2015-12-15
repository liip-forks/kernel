<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Kernel\AbstractLocator;

class MissingPropertyLocator extends AbstractLocator
{

    /**
     * @param $bundle
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
    }

}
