<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus_Cookie_Storage test
 *
 * @group  cerberus
 * @group  cerberus.storage
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Storage_CookieTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var  Cerberus_Storage_Cookie
	 */
	protected $_storage;

	public function setUp()
	{
		parent::setUp();

		$this->_storage = new Cerberus_Storage_Cookie;
	}

	/**
	 * @covers  Cerberus_Storage_Cookie::clear
	 *
	 * @return  void
	 */
	public function test_after_clear_storage_is_empty()
	{
		$this->_storage->write('junk');

		$this->_storage->clear();

		$this->assertTrue($this->_storage->is_empty());
	}

	/**
	 * @covers  Cerberus_Storage_Cookie::read
	 * @covers  Cerberus_Storage_Cookie::write
	 *
	 * @return  void
	 */
	public function test_rw()
	{
		$this->_storage->write('foobar');

		$this->assertSame('foobar', $this->_storage->read());
	}
}