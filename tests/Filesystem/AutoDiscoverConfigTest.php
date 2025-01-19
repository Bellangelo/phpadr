<?php declare(strict_types=1);

namespace ADR\Filesystem;

use PHPUnit\Framework\TestCase;

class AutoDiscoverConfigTest extends TestCase
{
    public function testGetConfigReturnsDefaultYaml(): void
    {
        $config = (new AutoDiscoverConfig())->getConfigPath('');

        $this->assertSame(realpath(__DIR__ . '/../../adr.yml'), $config);
    }

    public function testGetConfigReturnsPassedValue(): void
    {
        $config = (new AutoDiscoverConfig())->getConfigPath(__DIR__ . '/../../adr.yml');

        $this->assertSame(realpath(__DIR__ . '/../../adr.yml'), $config);
    }

    public function testGetConfigReturnsEmptyIfPassedValueDoesNotExist(): void
    {
        $config = (new AutoDiscoverConfig())->getConfigPath('does-not-exist.yml');

        $this->assertSame('', $config);
    }

    public function testGetConfigReturnsRootConfig(): void
    {
        $originalValue = $GLOBALS['_composer_autoload_path'];
        $GLOBALS['_composer_autoload_path'] = __DIR__ . '/../../vendor/autoload.php';

        try {
            $config = (new AutoDiscoverConfig())->getConfigPath('');
            $this->assertSame(realpath(__DIR__ . '/../../adr.yml'), $config);
        } finally {
            $GLOBALS['_composer_autoload_path'] = $originalValue;
        }
    }
}