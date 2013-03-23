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

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class VisitorsChartControl extends BaseChartControl
{

	/** @var string */
	protected $filterPath;

	/** @var string */
	protected $metrics = 'ga:newVisits,ga:visits,ga:pageviews';

	/**
	 * @param null $filterPath
	 */
	public function render($filterPath = NULL)
	{
		$args = func_get_args();

		if (isset($args[0]['filterPath'])) {
			$this->filterPath = $args[0]['filterPath'];
		}

		return call_user_func_array(array('parent', 'render'), $args);
	}


	protected function getGoogleAnalyticsData()
	{
		return $this->getGoogleAnalyticsService()->data_ga->get(
			'ga:' . $this->analyticsManager->getGaId(),
			$this->date[1]->format('Y-m-d'),
			$this->date[0]->format('Y-m-d'),
			$this->metrics,
			$this->getGoogleAnalyticsArgs()
		);
	}


	protected function getGoogleAnalyticsArgs()
	{
		$ret = array(
			'dimensions' => 'ga:date,ga:year,ga:month,ga:day',
			'max-results' => '31',
		);

		if ($this->filterPath) {
			$ret['filters'] = "ga:pagePath=~{$this->filterPath}/*";
		}

		return $ret;
	}
}
