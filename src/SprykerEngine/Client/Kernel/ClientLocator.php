<?php

namespace Spryker\Client\Kernel;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\ClassResolver\ClassNotFoundException;
use Spryker\Shared\Kernel\Locator\LocatorException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Library\Log;

class ClientLocator extends AbstractLocator
{

    const LOCATABLE_SUFFIX = 'Client';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $application = 'Client';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        $locatedClient = $factory->create($bundle . self::LOCATABLE_SUFFIX, $factory, $locator);

        try {
            $bundleDependencyProviderLocator = new BundleDependencyProviderLocator(); // TODO Make singleton because of performance
            $bundleBuilder = $bundleDependencyProviderLocator->locate($bundle, $locator);

            $container = new Container();
            $bundleBuilder->provideServiceLayerDependencies($container);
            $locatedClient->setExternalDependencies($container);
        } catch (ClassNotFoundException $e) {
            // TODO remove try-catch when all bundles have a Builder
            Log::log('Yves - ' . $bundle, 'builder_missing.log');
        }

        return $locatedClient;
    }

}
