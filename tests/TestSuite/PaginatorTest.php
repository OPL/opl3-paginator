<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite;
use Opl\Paginator\Paginator;

/**
 * @covers \Opl\Paginator\Paginator
 * @runTestsInSeparateProcesses
 */
class PaginatorTest extends \PHPUnit_Framework_TestCase
{
	public function testPaginatorCalculatesCorrectlySinglePage()
	{
		$paginator = new Paginator(10);
		$paginator->setElementNum(1);
		$paginator->process();
		
		$this->assertEquals(1, $paginator->getPageNum());
		$this->assertEquals(1, $paginator->getCurrentPage());
	} // end testPaginatorCalculatesCorrectlySinglePage();

	public function testPaginatorCalculatesCorrectlyFilledPages()
	{
		$paginator = new Paginator(10);
		$paginator->setElementNum(20);
		$paginator->process();
		
		$this->assertEquals(2, $paginator->getPageNum());
		$this->assertEquals(1, $paginator->getCurrentPage());
	} // end testPaginatorCalculatesCorrectlyFilledPages();

	public function testPaginatorCalculatesCorrectlyOverflows()
	{
		$paginator = new Paginator(10);
		$paginator->setElementNum(21);
		$paginator->process();
		
		$this->assertEquals(3, $paginator->getPageNum());
		$this->assertEquals(1, $paginator->getCurrentPage());
	} // end testPaginatorCalculatesCorrectlyOverflows();

	public function testPaginatorCalculatesCorrectlyLackOfElements()
	{
		$paginator = new Paginator(10);
		$paginator->setElementNum(0);
		$paginator->process();
		
		$this->assertEquals(0, $paginator->getPageNum());
		$this->assertEquals(0, $paginator->getCurrentPage());
	} // end testPaginatorCalculatesCorrectlyLackOfElements();
} // end PaginatorTest;