<?php

declare(strict_types=1);

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\FlowBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Flows extension.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class SyliusFlowExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration($configs, $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/container'));

        $container->setAlias('sylius.process_storage', $config['storage']);

        $configurations = [
            'builders',
            'validators',
            'contexts',
            'controllers',
            'coordinators',
            'storages',
        ];

        foreach ($configurations as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }
    }
}
