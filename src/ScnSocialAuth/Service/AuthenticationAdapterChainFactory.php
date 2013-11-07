<?php
/**
 * ScnSocialAuth Module
 *
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */

namespace ScnSocialAuth\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\AbstractAdapter;
use ZfcUser\Authentication\Adapter\AdapterChainServiceFactory;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class AuthenticationAdapterChainFactory implements FactoryInterface
{
    /**
     * @param  ServiceLocatorInterface                            $services
     * @return mixed|\ZfcUser\Authentication\Adapter\AdapterChain
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $scnSocialAuth_options = $services->get('ScnSocialAuth-ModuleOptions');

        $factory = new AdapterChainServiceFactory();

        if ($scnSocialAuth_options instanceof \ScnSocialAuth\Options\ModuleOptions && $scnSocialAuth_options->getShareAuthAdaptersStorageInChain()) {
            $options = $factory->getOptions($services);

            $authAdapter = $services->get('ScnSocialAuth\Authentication\Adapter\HybridAuth');

            foreach ($options->getAuthAdapters() as $adapterName) {
                $adapter = $services->get($adapterName);
                if ($adapter instanceof AbstractAdapter) {
                    $adapter->setStorage($authAdapter->getStorage());
                }
            }
        }

        return $factory->createService($services);
    }
}
