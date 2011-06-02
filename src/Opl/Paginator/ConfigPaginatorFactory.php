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
use Opl\Collector\ProviderInterface;
use Opl\Paginator\Decorator\DecoratorInterface;

/**
 * This is a default paginator factory implementation that configures
 * the paginator using the settings from the configuration. The implementation
 * requires the Open Power Collector library to work.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class ConfigPaginatorFactory implements PaginatorFactoryInterface
{
	protected $itemsPerPage;
	protected $decoratorMappings = array(
		'slider' => 'Opl\\Paginator\\Decorator\\SliderDecorator',
		'firstLast' => 'Opl\\Paginator\\Decorator\\FirstLastDecorator',
		'prevNext' => 'Opl\\Paginator\\Decorator\\PrevNextDecorator',
		'boundary' => 'Opl\\Paginator\\Decorator\\BoundaryDecorator',
		'steps' => 'Opl\\Paginator\\Decorator\\StepDecorator',
		'clicks' => 'Opl\\Paginator\\Decorator\\ClickDecorator'
	);
	protected $decorators = array();
	protected $provider;

	/**
	 * Reads the configuration from the given configuration provider.
	 * 
	 * @param \Opl\Collector\ProviderInterface $provider 
	 */
	public function __construct(ProviderInterface $provider)
	{
		$this->provider = $provider;
		$this->itemsPerPage = $provider->get('itemsPerPage');
		$this->decorators = explode(',', $provider->get('decorators'));
	} // end __construct();
	
	/**
	 * Registers a new decorator class within the factory, so that it
	 * can be used in the configuration files under the given name.
	 * Implements fluent interface.
	 * 
	 * @param string $name The decorator name visible in the configuration
	 * @param string $className The fully qualified decorator class name
	 * @return ConfigPaginatorFactory
	 */
	public function registerDecorator($name, $className)
	{
		$this->decoratorMappings[(string)$name] = (string)$className;
		
		return $this;
	} // end registerDecorator();
	
	/**
	 * Constructs the paginator, using the settings from the configuration.
	 * 
	 * @return Paginator 
	 */
	public function getPaginator()
	{
		return new Paginator($this->itemsPerPage);
	} // end getPaginator();
	
	/**
	 * Constructs a decorator chain for the given paginator which can be
	 * used to render the list of pages. If the configuration refers to
	 * an unregistered decorator, an exception is thrown.
	 * 
	 * @throws \Opl\Paginator\Exception\DecoratorException
	 * @throws \Opl\Paginator\Exception\PaginatorFactoryException
	 * @param Paginator $paginator The paginator to decorate
	 * @return \Opl\Paginator\Decorator\DecoratorInterface
	 */
	public function decorate(Paginator $paginator)
	{
		$previousDecorator = null;
		foreach($this->decorators as $decorator)
		{
			$decorator = $this->getDecorator($decorator);
			$decorator->setPaginator($paginator);
			if(null !== $previousDecorator)
			{
				$decorator->decorate($previousDecorator);
			}
			$previousDecorator = $decorator;
		}
		return $decorator;
	} // end decorate();
	
	/**
	 * Constructs a single decorator object for the given name.
	 * 
	 * @throws \Opl\Paginator\Exception\PaginatorFactoryException
	 * @param string $name The decorator name
	 * @return \Opl\Paginator\Decorator\DecoratorInterface 
	 */
	public function getDecorator($name)
	{
		if(!isset($this->decoratorMappings[$name]))
		{
			throw new PaginatorFactoryException('Cannot find the pagination decorator: '.$name);
		}
		$className = $this->decoratorMappings[$name];
		
		$decorator = new $className;
		if(!$decorator instanceof DecoratorInterface)
		{
			throw new PaginatorFactoryException('The specified decorator does not implement the necessary interface.');
		}
		
		$config = $this->provider->get($name, ProviderInterface::THROW_NULL);
		if(null !== $config)
		{
			$decorator->setConfig($config);
		}
		return $decorator;
	} // end getDecorator();
} // end ConfigPaginatorFactory;