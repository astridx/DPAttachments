<?php
/**
 * @package    DPAttachments
 * @copyright  Copyright (C) 2013 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

use DigitalPeak\Component\DPAttachments\Administrator\Extension\DPAttachmentsComponent;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class () implements ServiceProviderInterface {
	public function register(Container $container)
	{
		$container->registerServiceProvider(new MVCFactory('\\DigitalPeak\\Component\\DPAttachments'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\DigitalPeak\\Component\\DPAttachments'));

		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new DPAttachmentsComponent(
					Factory::getApplication(),
					$container->get(DatabaseInterface::class),
					$container->get(ComponentDispatcherFactoryInterface::class)
				);

				$component->setMVCFactory($container->get(MVCFactoryInterface::class));

				return $component;
			}
		);
	}
};
