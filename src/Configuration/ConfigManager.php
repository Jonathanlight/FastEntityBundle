<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Configuration;

use Jonathankablan\Bundle\FastEntityBundle\Exception\UndefinedEntityException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Contracts\Cache\CacheInterface;

final class ConfigManager
{
    private const CACHE_KEY = 'fastentity.processed_config';

    /** @var array */
    private $originalBackendConfig;
    /** @var ConfigPassInterface[] */
    private $configPasses;

    public function __construct(array $originalBackendConfig)
    {
        $this->originalBackendConfig = $originalBackendConfig;
    }

    /**
     * @param ConfigPassInterface $configPass
     */
    public function addConfigPass(ConfigPassInterface $configPass)
    {
        $this->configPasses[] = $configPass;
    }
}
