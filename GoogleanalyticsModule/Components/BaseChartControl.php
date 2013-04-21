<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\Components;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\Strings;
use Venne\Application\UI\Control;
use GoogleanalyticsModule\AnalyticsManager;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class BaseChartControl extends Control
{

	/** @var AnalyticsManager */
	protected $analyticsManager;

	/** @var Cache */
	protected $cache;

	/** @var array */
	protected $size = array('100%', 400);

	/** @var array */
	protected $date = array();

	/** @var string */
	protected $history;

	/** @var string */
	protected $metrics = '';

	/** @var array */
	protected $options;

	/** @var  bool */
	protected $toolbar = FALSE;


	public function __construct(AnalyticsManager $analyticsManager, FileStorage $fileStorage)
	{
		parent::__construct();

		$this->analyticsManager = $analyticsManager;
		$this->cache = new Cache($fileStorage, 'GA');
		$this->setHistory();
	}


	protected function setHistory($history = '-1 week')
	{
		$now = new \DateTime;
		$prev = new \DateTime;
		$prev->add(\DateInterval::createFromDateString($history));
		$this->date = array($now, $prev);
	}


	public function getSize()
	{
		return $this->size;
	}


	public function getMetrics()
	{
		return $this->metrics;
	}


	public function getOptions()
	{
		return $this->options;
	}


	public function getHistory()
	{
		return $this->history;
	}


	public function getToolbar()
	{
		return $this->toolbar;
	}


	public function handleLoad($args = array())
	{
		if (!$this->presenter->isAjax()) {
			$this->redirect('this');
		}

		$this->setArguments($args);

		$this->template->ajax = TRUE;
		$this->invalidateControl('data');
		$this->presenter->payload->url = $this->link('this');
	}


	public function setArguments()
	{
		$args = func_get_args();

		if (isset($args[0]['size'])) {
			$this->size = $args[0]['size'];
		}

		if (isset($args[0]['history'])) {
			$this->history = $args[0]['history'];
			$this->setHistory($args[0]['history']);
		}

		if (isset($args[0]['metrics'])) {
			$this->metrics = $args[0]['metrics'];
		}

		if (isset($args[0]['options'])) {
			$this->options = $args[0]['options'];
		}

		if (isset($args[0]['toolbar'])) {
			$this->toolbar = $args[0]['toolbar'];
		}
	}


	public function render()
	{
		call_user_func_array(array($this, 'setArguments'), func_get_args());

		if (isset($this->template->ajax)) {
			$this->renderChart();
			return;
		}

		if ($this->analyticsManager->getApiActivated()) {
			$this->template->render();
		} else {
			$this->flashMessage('Google analytics API is deactivated.', 'info');
			$this->template->error = TRUE;
			$this->template->render();
		}
	}


	public function renderChart($return = FALSE)
	{
		$key = $this->getKey();
		$ret = $this->cache->load($key);
		if (!$ret) {

			try {
				$this->template->data = $this->getGoogleAnalyticsData();
			} catch (\Google_AuthException $e) {
				$this->flashMessage($e->getMessage(), 'warning');
				$this->template->error = TRUE;
			} catch (\Google_ServiceException $e) {
				$this->flashMessage($e->getMessage(), 'warning');
				$this->template->error = TRUE;
			}

			ob_start();
			$this->template->__toString();
			$ret = ob_get_clean();
			$this->cache->save($key, $ret, array(
				Cache::EXPIRE => '+ 30 minutes',
			));
		}

		if ($return) {
			return $ret;
		}

		echo $ret;
	}


	protected function getKey()
	{
		return array(
			$this->name,
			$this->size,
			$this->history,
			$this->metrics,
			$this->options,
			$this->analyticsManager->getApiActivated(),
			$this->analyticsManager->getClientId(),
			$this->analyticsManager->getClientMail(),
			$this->analyticsManager->getGaId(),
			$this->getGoogleAnalyticsArgs(),
			$this->toolbar,
		);
	}


	public function isInCache()
	{
		return $this->cache->load($this->getKey());
	}


	protected function getGoogleAnalyticsData()
	{
	}


	protected function getGoogleAnalyticsArgs()
	{
		return array();
	}


	protected function getGoogleAnalyticsService()
	{
		$au = $this->presenter->absoluteUrls;
		$this->presenter->absoluteUrls = TRUE;
		$scriptUri = $this->link('this');
		$this->presenter->absoluteUrls = $au;

		return $this->analyticsManager->getGoogleAnalyticsService($scriptUri);
	}
}
