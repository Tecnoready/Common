<?php

namespace Tecnoready\Common\Tests\Service\ConfigurationService;

use PHPUnit\Framework\TestCase;
use Tecnoready\Common\Model\Configuration\BaseEntity\DoctrineORMConfiguration;
use Tecnoready\Common\Service\ConfigurationService\Adapter\ScopedConfigurationAdapterInterface;
use Tecnoready\Common\Service\ConfigurationService\CacheInterface;
use Tecnoready\Common\Service\ConfigurationService\ConfigurationManager;

class ConfigurationManagerTest extends TestCase
{
    public function testCacheMissLoadsAndCachesOnlyRequestedConfiguration(): void
    {
        $configuration = new class extends DoctrineORMConfiguration {
            public function getId()
            {
                return null;
            }
        };
        $configuration
            ->setKey('DEFAULT_CURRENCY_WALLET')
            ->setNameWrapper('WRAPPER_CORE')
            ->setValue('USD')
            ->setType('string')
            ->setDataType(null)
            ->setDescription('Default wallet currency');

        $adapter = $this->createMock(ScopedConfigurationAdapterInterface::class);
        $adapter->expects(self::once())
            ->method('findByKeyAndWrapper')
            ->with('DEFAULT_CURRENCY_WALLET', 'WRAPPER_CORE')
            ->willReturn($configuration);

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects(self::once())->method('setAdapter')->with($adapter);
        $cache->expects(self::once())
            ->method('getConfiguration')
            ->with('DEFAULT_CURRENCY_WALLET', 'WRAPPER_CORE')
            ->willReturn(null);
        $cache->expects(self::once())
            ->method('save')
            ->with('DEFAULT_CURRENCY_WALLET', 'WRAPPER_CORE', [
                'value' => 'USD',
                'type' => 'string',
                'data_type' => null,
                'description' => 'Default wallet currency',
            ]);
        $cache->expects(self::never())->method('flush');
        $cache->expects(self::never())->method('warmUp');

        $manager = new ConfigurationManager($adapter, $cache);

        self::assertSame('USD', $manager->get('DEFAULT_CURRENCY_WALLET', 'WRAPPER_CORE'));
    }
}