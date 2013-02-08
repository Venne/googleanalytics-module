<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\DI;

use Venne;
use Venne\Config\CompilerExtension;
use Nette\Application\Routers\Route;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class GoogleanalyticsExtension extends CompilerExtension
{

	/** @var array */
	public $defaults = array(
		'account' => array(
			'activated' => FALSE,
			'accountId' => '',
		),

	);


	/**
	 * Processes configuration data. Intended to be overridden by descendant.
	 * @return void
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$container->addDefinition($this->prefix('analyticsManager'))
			->setClass('GoogleanalyticsModule\AnalyticsManager', array($config['account']['activated'], $config['account']['accountId']));
	}
}
