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
 * Slider decorator produces the links to the given range of pages around
 * the current active page, i.e <tt> 5 6 7 [8] 9 10 11</tt>.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class SliderDecorator implements DecoratorInterface
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
	 * Default slider range.
	 * @var integer
	 */
	protected $range = 2;
	
	/**
	 * @see DecoratorInterface
	 */
	public function setConfig(ProviderInterface $provider)
	{
		$this->range = $provider->get('range');
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
		$pages = array();
		$currentPage = $this->paginator->getCurrentPage();
		$pageNumber = $this->paginator->getPageNum();
		
		$realPreviousRange = (($currentPage - $this->range) < 1 ? ($currentPage - 1) : $this->range);
		$realNextRange = (($currentPage + $this->range) > $pageNumber ? ($pageNumber - $currentPage) : $this->range);
		$page = $currentPage - $realPreviousRange;
		for($i = 0; $i < $realPreviousRange; $i++)
		{
			$pages[] = array(
				'type' => 'page',
				'page' => $page
			);
			$page++;
		}
		$pages[] = array(
			'type' => 'current',
			'page' => $page
		);
		for($i = 0; $i < $realNextRange; $i++)
		{
			$pages[] = array(
				'type' => 'page',
				'page' => $page
			);
			$page++;
		}
		return $pages;
	} // end getPages();
} // end SliderDecorator;