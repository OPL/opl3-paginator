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

/**
 * This interface is an implementation of the abstract factory
 * design pattern. It allows to build factories that construct
 * both the paginators using various criteria, and the decorator
 * chains to display the pagination.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
interface PaginatorFactoryInterface
{
	/**
	 * The method returns a preconfigured paginator object. The method
	 * is allowed to throw <tt>PaginatorFactoryException</tt> exceptions.
	 * 
	 * @throws \Opl\Paginator\Exception\PaginatorFactoryException
	 * @return \Opl\Paginator\Paginator
	 */
	public function getPaginator();
	
	/**
	 * The method returns a decorator chain for the given paginator. The
	 * decorator chain produces a list of pages that can be later rendered
	 * in the view. The method is allowed to throw <tt>PaginatorFactoryException</tt> exceptions.
	 * 
	 * @throws \Opl\Paginator\Exception\PaginatorFactoryException
	 * @return \Opl\Paginator\Decorator\DecoratorInterface
	 */
	public function decorate(Paginator $paginator);
} // end PaginatorFactoryInterface;