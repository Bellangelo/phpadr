<?php declare(strict_types=1);

namespace ADR\Filesystem;

class AutoDiscoverConfig
{
    private const DEFAULT_CONFIG = __DIR__ . '/../../adr.yml';

    private function getRootConfig(): ?string
    {
        if (empty($GLOBALS['_composer_autoload_path'])) {
            return null;
        }

        // dirname($GLOBALS['_composer_autoload_path']) should point to the vendor folder.
        $rootPath = realpath(dirname($GLOBALS['_composer_autoload_path']) . '/../');

        if (file_exists($rootPath . '/adr.yml')) {
            return $rootPath . '/adr.yml';
        }

        return null;
    }

    public function getConfig(string $configLocation): Config
    {
        if (empty($configLocation)) {
            return new Config($this->getRootConfig() ?? realpath(self::DEFAULT_CONFIG));
        }

        return new Config(realpath($configLocation) ?: '');
    }
}