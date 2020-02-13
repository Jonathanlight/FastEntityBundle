<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Parser;

class SettingManager
{
    const FAST_ENTITY = 'fast_entity.yaml';

    protected $configDirectories;

    protected $yaml;

    public function __construct()
    {
        $this->yaml = new Parser();
    }

    public function settingYamlConfig()
    {
        $path = explode('vendor',__DIR__);
        $this->configDirectories = [$path[0].'config/packages'];

        $fileLocator = new FileLocator($this->configDirectories);

        return $fileLocator->locate(self::FAST_ENTITY, null, false);
    }

    /**
     * @return mixed
     */
    public function readYamlConfig()
    {
        $yamlFiles = $this->settingYamlConfig();

        if (!file_get_contents($yamlFiles[0])) {
            throw new \InvalidArgumentException(sprintf('Source of path is not found - "%s" ', $this->yamlFiles[0]));
        }

        $contentYaml = $this->yaml->parse(file_get_contents($yamlFiles[0]));

        return $contentYaml;
    }
}