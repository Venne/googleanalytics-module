<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule;

use Nette\Object;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AnalyticsManager extends Object
{

	/** @var bool */
	protected $activated;

	/** @var string */
	protected $accountId;


	/**
	 * @param $activated
	 * @param $accountId
	 */
	public function __construct($activated, $accountId)
	{
		$this->activated = $activated;
		$this->accountId = $accountId;
	}


	/**
	 * @return boolean
	 */
	public function getActivated()
	{
		return $this->activated;
	}


	/**
	 * @return string
	 */
	public function getAccountId()
	{
		return $this->accountId;
	}
}
