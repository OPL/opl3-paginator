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
 * The paginator represents a single pagination case, with a particular amount
 * of data, number of items per page, and the current page number. The paginator
 * object only processes the data and performs the calculations. The displaying
 * is not a responsibility of it. In order to display the pages, please use
 * the decorators and appropriate template constructs.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Paginator
{
	const STATE_INITIAL = 0;
	const STATE_DIRTY = 1;
	const STATE_CORRECT = 2;

	/**
	 * Controls the paginator state, whether the returned results are correct or not.
	 * @var integer
	 */
	protected $state = 0;
	
	/**
	 * The number of elements to paginate.
	 * @var integer
	 */
	protected $elementNum = null;
	/**
	 * The maximum number of items per page.
	 * @var integer
	 */
	protected $itemsPerPage;
	/**
	 * The number of pages that can contain the given number of elements.
	 * @var integer
	 */
	protected $pageNumber;
	/**
	 * The current page number; must be greater than 0.
	 * @var integer
	 */
	protected $currentPage = 1;
	
	/**
	 * Initializes the object. In the argument, we specify the number of
	 * items per page. If the specified value does not match the valid data
	 * domain, an exception is thrown.
	 * 
	 * @throws PaginatorConfigException
	 * @param integer $itemsPerPage The number of items per page 
	 */
	public function __construct($itemsPerPage)
	{
		$this->setItemsPerPage($itemsPerPage);
	} // end __construct();
	
	/**
	 * Sets the number of elements to paginate. This method is obligatory
	 * to call. Implements fluent interface.
	 * 
	 * @throws PaginatorConfigException If the element number is negative.
	 * @param integer $elementNum The number of elements to paginate.
	 * @return Paginator 
	 */
	public function setElementNum($elementNum)
	{
		if($elementNum < 0)
		{
			throw new PaginatorConfigException('The pagination element number cannot be negative.');
		}
		$this->state = ($this->state == self::STATE_CORRECT ? self::STATE_DIRTY : $this->state);
		$this->elementNum = (int)$elementNum;
		return $this;
	} // end setElementNum();
	
	/**
	 * Sets the current page number obtained from the input. If this method is not
	 * called, or the specified value is not a valid positive integer, the current
	 * page is assumed to be 1. Implements fluent interface.
	 * 
	 * @param int $currentPage The current page number.
	 * @return Paginator 
	 */
	public function setCurrentPage($currentPage)
	{
		if(!ctype_digit($currentPage) || $currentPage < 0)
		{
			$currentPage = 1;
		}
		$this->state = ($this->state == self::STATE_CORRECT ? self::STATE_DIRTY : $this->state);
		$this->currentPage = (int)$currentPage;
		return $this;
	} // end setCurrentPage();
	
	/**
	 * Sets the number of items to display per single page. If the specified value
	 * is lower than 1, an exception is thrown. Implements fluent interface.
	 * 
	 * @throws PaginatorConfigException
	 * @param integer $itemsPerPage The number of items per page.
	 * @return Paginator
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		if($itemsPerPage < 1)
		{
			throw new PaginatorConfigException('The pagination number of items per page must be greater than or equal 1.');
		}
		$this->state = ($this->state == self::STATE_CORRECT ? self::STATE_DIRTY : $this->state);
		$this->itemsPerPage = (int)$itemsPerPage;
		return $this;
	} // end setItemsPerPage();
	
	/**
	 * Returns the number of items per page.
	 * 
	 * @return integer 
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	} // end getItemsPerPage();
	
	/**
	 * Returns the current page number.
	 * 
	 * @return integer 
	 */
	public function getCurrentPage()
	{
		return $this->currentPage;
	} // end getCurrentPage();
	
	/**
	 * Returns the element number.
	 * 
	 * @return integer 
	 */
	public function getElementNum()
	{
		return $this->elementNum;
	} // end getElementNum();
	
	/**
	 * Returns the total number of pages.
	 * 
	 * @return integer
	 */
	public function getPageNum()
	{
		return $this->pageNumber;
	} // end getPageNum();
	
	/**
	 * Returns the current paginator state: uninitialized,
	 * dirty (the data have been changed since the last calculation)
	 * or correct.
	 * 
	 * @return integer 
	 */
	public function getState()
	{
		return $this->state;
	} // end getState();
	
	/**
	 * Performs the pagination calculations, discovering the total number of
	 * pages and changing the state to correct. If the element number is not
	 * specified, an exception is thrown. Implements fluent interface.
	 * 
	 * @throws PaginatorException
	 * @return Paginator
	 */
	public function process()
	{
		if(null === $this->elementNum)
		{
			throw new PaginatorException('Please specify the number of elements.');
		}
		
		$rest = ($this->elementNum % $this->itemsPerPage);
		$this->pageNumber = (($this->elementNum - $rest) / $this->itemsPerPage);
		
		if($rest > 0)
		{
			$this->pageNumber++;
		}
		
		if($this->currentPage > $this->pageNumber)
		{
			$this->currentPage = $this->pageNumber;
		}
		$this->state = self::STATE_CORRECT;
		
		return $this;
	} // end process();

	/**
	 * Returns the offset, that is the number of the element which begins
	 * the current active page. Together with the <tt>items per page</tt>
	 * property it allows for example to build a proper LIMIT clause for
	 * SQL queries.
	 * 
	 * If the element number is 0, the method returns 0.
	 * 
	 * @return integer 
	 */
	public function getOffset()
	{
		if($this->elementNum == 0)
		{
			return 0;
		}
		return ($this->currentPage - 1) * $this->itemsPerPage;
	} // end getOffset();
} // end Paginator;
