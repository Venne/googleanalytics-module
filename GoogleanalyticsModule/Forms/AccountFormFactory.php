<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\Forms;

use Venne;
use Venne\Forms\Form;
use Venne\Forms\FormFactory;
use FormsModule\Mappers\ConfigMapper;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AccountFormFactory extends FormFactory
{


	/** @var ConfigMapper */
	protected $mapper;


	/**
	 * @param ConfigMapper $mapper
	 */
	public function __construct(ConfigMapper $mapper)
	{
		$this->mapper = $mapper;
	}


	protected function getMapper()
	{
		$mapper = clone $this->mapper;
		$mapper->setRoot('googleanalytics.account');
		return $mapper;
	}


	/**
	 * @param Form $form
	 */
	protected function configure(Form $form)
	{
		$form->addGroup("Account");
		$form->addCheckbox('activated', 'Activate')->addCondition($form::EQUAL, TRUE)->toggle('form-account-accountId');

		$form->addGroup()->setOption('id', 'form-account-accountId');
		$form->addText('accountId', 'Account ID');

		$form->addGroup();
		$form->addSaveButton('Save');
	}
}
