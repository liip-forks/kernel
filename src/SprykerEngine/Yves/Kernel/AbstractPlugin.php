<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Yves\Kernel\DependencyContainer\DependencyContainerInterface;

abstract class AbstractPlugin
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    const CLASS_PART_BUNDLE = 2;

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var AbstractClient
     */
    private $client;

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $factory = new Factory($this->getBundleName());

            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $this->getLocator());
        }

        return $this->dependencyContainer;
    }

    /**
     * @return AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $bundleName = lcfirst($this->getBundleName());
            $this->client = $this->getLocator()->$bundleName()->client();
        }

        return $this->client;
    }

    /**
     * @return string
     */
    protected function getBundleName()
    {
        $className = get_class($this);
        $classParts = explode('\\', $className);
        $bundle = $classParts[self::CLASS_PART_BUNDLE];

        return $bundle;
    }

    /**
     * @return LocatorInterface
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
