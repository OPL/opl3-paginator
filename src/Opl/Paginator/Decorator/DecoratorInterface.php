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

interface DecoratorInterface
{
	public function setConfig(ProviderInterface $provider);
	public function setPaginator(Paginator $paginator);
	public function getPaginator();
	public function decorate(DecoratorInterface $decorator);
	public function getPages();
} // end DecoratorInterface;