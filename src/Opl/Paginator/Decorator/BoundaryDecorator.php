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
 * Slider decorator produces the links at the beginning and the end of the list
 * of pages: [1] 2 3 ... 31 32 33
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class BoundaryDecorator implements DecoratorInterface
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
	 * Default boundary range.
	 * @var integer
	 */
	protected $range = 2;
	/**
	 * Do we mark and produce gaps?
	 * @var boolean
	 */
	protected $gaps = false;
	
	/**
	 * @see DecoratorInterface
	 */
	public function setConfig(ProviderInterface $provider)
	{
		$this->range = (int)$provider->get('range', ProviderInterface::THROW_NULL);
		$this->gaps = (boolean)$provider->get('gaps', ProviderInterface::THROW_NULL);
		
		if(null === $this->range)
		{
			$this->range = 2;
		}
		if(null === $this->gaps)
		{
			$this->gaps = true;
		}
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
	public function getPages()
	{
		if(null === $this->paginator)
		{
			throw new DecoratorException('The paginator object is not defined for '.get_class($this));
		}
		
		$pages = $this->decorator->getPages();
		
		// Find the boundary pages from the previous decorator.
		$firstPage = null;
		$lastPage = null;
		foreach($pages as $page)
		{
			if($page['type'] == 'page')
			{
				if(null === $firstPage)
				{
					$firstPage = $page['page'];
				}
				$lastPage = $page['page'];
			}
		}
		
		$decoratedPages = array();
		$currentPage = $this->paginator->getCurrentPage();
		$pageNumber = $this->paginator->getPageNum();
		$lowerBoundary = ($this->range < $firstPage ? $this->range : $firstPage);
		$upperBoundary = ($pageNumber - $range > $lastPage ? $pageNumber - $range : $lastPage);
		
		for($i = 1; $i < $lowerBoundary; $i++)
		{
			$pages[] = array(
				'type' => ($currentPage == $i ? 'current' : 'page'),
				'page' => $i
			);
		}
		if($firstPage - $i > 1)
		{
			$pages[] = array('type' => 'gap');
		}
		foreach($decoratedPages as $page)
		{
			$pages[] = $page;
		}
		if($upperBoundary - $lastPage > 1)
		{
			$pages[] = array('type' => 'gap');
		}
		for($i = $upperBoundary; $i <= $pageNumber; $i++)
		{
			$pages[] = array(
				'type' => ($currentPage == $i ? 'current' : 'page'),
				'page' => $i
			);
		}
		return $pages;
	} // end getPages();
} // end BoundaryDecorator;