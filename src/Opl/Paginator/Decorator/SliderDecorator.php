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

class SliderDecorator implements DecoratorInterface
{
	protected $decorator;
	protected $paginator;
	protected $range = 2;
	
	public function setConfig(ProviderInterface $provider)
	{
		$this->range = $provider->get('range');
	} // end setConfig();

	public function setPaginator(Paginator $paginator)
	{
		$this->paginator = $paginator;
	} // end setPaginator();
	
	public function getPaginator()
	{
		return $this->paginator;
	} // end getPaginator();
	
	public function decorate(DecoratorInterface $decorator)
	{
		$this->decorator = $decorator;
		return $decorator;
	} // end decorate();

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