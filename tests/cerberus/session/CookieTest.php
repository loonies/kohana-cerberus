<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test the Cerberus_Cookie_Session class
 *
 * @group  cerberus
 * @group  cerberus.session
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Storage_CookieTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var  Cerberus_Session_Cookie
	 */
	protected $_session;

	public function setUp()
	{
		parent::setUp();

		$this->_session = new Cerberus_Session_Cookie;
	}

	/**
	 * @covers  Cerberus_Session_Cookie::clear
	 *
	 * @return  void
	 */
	public function test_after_clear_session_is_empty()
	{
		$this->_session->write('junk');

		$this->_session->clear();

		$this->assertTrue($this->_session->is_empty());
	}

	/**
	 * @covers  Cerberus_Session_Cookie::read
	 * @covers  Cerberus_Session_Cookie::write
	 *
	 * @return  void
	 */
	public function test_rw()
	{
		$this->_session->write('foobar');

		$this->assertSame('foobar', $this->_session->read());
	}
}