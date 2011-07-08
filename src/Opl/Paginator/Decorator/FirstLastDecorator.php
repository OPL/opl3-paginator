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
use Opl\Paginator\Exception\DecoratorException;

/**
 * This decorator emits the [First] and [Last] links wrapping the decorated
 * set. The links refer to the first and the last page.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class FirstLastDecorator implements DecoratorInterface
{
	/**
	 * The decorated object
	 * @var DecoratorInterface
	 */
	protected $decorator;
	/**
	 * The handled paginator
	 * @var Paginator
	 */
	protected $paginator;
	
	/**
	 * @see DecoratorInterface
	 */
	public function setConfig(ProviderInterface $provider)
	{
		/* null */
	} // end setConfig();

	/**
	 * @see DecoratorInterface
	 */
	public function setPaginator(Paginator $paginator)
	{
		$this->paginator = $paginator;
	} // end setPaginator();
	
	/**
	 * @see DecoratorInterface
	 */
	public function getPaginator()
	{
		return $this->paginator;
	} // end getPaginator();
	
	/**
	 * @see DecoratorInterface
	 */
	public function decorate(DecoratorInterface $decorator)
	{
		$this->decorator = $decorator;
		return $decorator;
	} // end decorate();
	
	/**
	 * @see DecoratorInterface
	 */
	public function getDecorator()
	{
		return $this->decorator;
	} // end getDecorator();
	
	/**
	 * @see DecoratorInterface
	 */
	public function getPages()
	{
		if(null === $this->paginator)
		{
			throw new DecoratorException('The paginator object is not defined for '.get_class($this));
		}
		if(null === $this->decorator)
		{
			$pages = array();
		}
		else
		{
			$pages = $this->decorator->getPages();
		}
		$currentPage = $this->paginator->getCurrentPage();
		$pageNumber = $this->paginator->getPageNum();
		
		$newPages = array();
		if($pageNumber > 1)
		{
			$newPages[] = array(
				'type' => 'first',
				'page' => 1
			);
		}
		foreach($pages as $page)
		{
			$newPages[] = $page;
		}
		if($pageNumber > 1)
		{
			$newPages[] = array(
				'type' => 'last',
				'page' => $pageNumber
			);	
		}
		return $newPages;
	} // end getPages();
} // end FirstLastDecorator;