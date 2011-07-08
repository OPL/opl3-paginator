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
namespace Opl\Paginator;
use Opl\Paginator\Decorator\DecoratorInterface;
use Opl\Paginator\Exception\PaginatorFactoryException;

/**
 * The default paginator factory can be configured directly from the source
 * code level.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class DefaultPaginatorFactory implements PaginatorFactoryInterface
{
	/**
	 * The number of items per page the paginators are initialized with.
	 * @var int
	 */
	protected $itemsPerPage = 15;
	/**
	 * Decorator chain.
	 * @var DecoratorInterface
	 */
	protected $decorators = null;
	
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = (int) $itemsPerPage;
		return $this;
	} // end setItemsPerPage();
	
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	} // end getItemsPerPage();
	
	public function setDecoratorChain(DecoratorInterface $decorator)
	{
		$this->decorators = $decorator;
		return $this;
	} // end setDecorator();
	
	public function getDecoratorChain()
	{
		return $this->decorator;
	} // end getDecoratorChain();
	
	public function getPaginator()
	{
		return new Paginator($this->itemsPerPage);
	} // end getPaginator();
	
	public function decorate(Paginator $paginator)
	{
		$decorator = $this->decorators;
		
		if(null === $decorator)
		{
			throw new PaginatorFactoryException('Cannot decorate a paginator: no decorators defined.');
		}
		
		while(null !== $decorator)
		{
			$decorator->setPaginator($paginator);
			$decorator = $decorator->getDecorator();
		}
		return $this->decorators;
	} // end decorate();
} // end DefaultPaginatorFactory;