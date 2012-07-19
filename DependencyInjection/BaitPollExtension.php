<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class BaitPollExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        if (!in_array(strtolower($config['db_driver']), array('orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid database driver (bait_poll.db_driver) "%s".', $config['db_driver']));
        }

        $loader->load(sprintf('%s.xml', $config['db_driver']));

        $container->setParameter('bait_poll.model_manager_name', $config['model_manager_name']);

        $container->setParameter('bait_poll.form.type', $config['form']['type']);
        $container->setParameter('bait_poll.form.name', $config['form']['name']);
        $container->setAlias('bait_poll.form.factory', $config['form']['factory']);

        $container->setParameter('bait_poll.poll.class', $config['poll']['class']);
        $container->setParameter('bait_poll.field.class', $config['field']['class']);
        $container->setParameter('bait_poll.vote.class', $config['vote']['class']);
        $container->setParameter('bait_poll.form.template', $config['form']['template']);
        $container->setParameter('bait_poll.form.theme', $config['form']['theme']);

        $container->setAlias('bait_poll.vote.manager', $config['vote']['manager']);
        $container->setAlias('bait_poll.poll.manager', $config['poll']['manager']);
        $container->setAlias('bait_poll.field.manager', $config['field']['manager']);

        $loader->load('form.xml');

        $container->setParameter('bait_poll.cookie.prefix', $config['cookie']['prefix']);
        $container->setParameter('bait_poll.cookie.duration', $config['cookie']['duration']);

        $loader->load('poll.xml');
    }
}
