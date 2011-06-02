<?php
/*
 *  OPEN POWER LIBS <http://www.invenzzia.org>
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 */
namespace Opl\Paginator\Decorator;
use Opl\Collector\ProviderInterface;
use Opl\Paginator\Paginator;

/**
 * The interface allows us to write new pagination decorators.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
interface DecoratorInterface
{
	/**
	 * Passes the configuration instance to this decorator. The method is
	 * guaranteed to get the provider containing its own options only.
	 * 
	 * @param Opl\Collector\ProviderInterface $provider The configuration provider
	 */
	public function setConfig(ProviderInterface $provider);
	
	/**
	 * Sets the pagination object which keeps the pagination data.
	 * 
	 * @param Paginator $paginator The paginator.
	 */
	public function setPaginator(Paginator $paginator);
	/**
	 * Returns the processed paginator.
	 * 
	 * @return Paginator
	 */	
	public function getPaginator();
	
	/**
	 * Passes the decorator which is decorated by us.
	 * 
	 * @param DecoratorInterface $decorator The decorated object.
	 */
	public function decorate(DecoratorInterface $decorator);
	/**
	 * Produces an array with the control commands that allow the view layer
	 * to draw a pagination list. The method is obliged to concatenate its
	 * own additions with the commands specified by the lower-level decorators.
	 * 
	 * @return array
	 */
	public function getPages();
} // end DecoratorInterface;