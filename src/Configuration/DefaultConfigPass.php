<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Configuration;

/**
 * Processes default values for some backend configuration options.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class DefaultConfigPass implements ConfigPassInterface
{
    public function process(array $backendConfig)
    {
        $backendConfig = $this->processDefaultEntity($backendConfig);

        return $backendConfig;
    }

    /**
     * Finds the default entity to display when the backend index is not
     * defined explicitly.
     *
     * @param array $backendConfig
     *
     * @return array
     */
    private function processDefaultEntity(array $backendConfig)
    {
        $entityNames = array_keys($backendConfig['entities']);
        $firstEntityName = $entityNames[0] ?? null;
        $backendConfig['default_entity_name'] = $firstEntityName;

        return $backendConfig;
    }
}
