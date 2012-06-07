<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus_Storage_Cookie test
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
	 * Provider for [test_it_sets_config_options]
	 *
	 * @return  array
	 */
	public function provider_it_sets_config_options()
	{
		return array(
			array(
				array('name' => 'foo', 'lifetime' => 2),
				array('name' => 'foo', 'lifetime' => 2),
			),
			array(
				array(),
				array('name' => 'cerberus', 'lifetime' => Date::WEEK),
			),
		);
	}

	public function test_it_implements_storage_interface()
	{
		$this->assertInstanceOf('Cerberus_Storage', new Cerberus_Storage_Cookie);
	}

	/**
	 * @covers  Cerberus_Storage_Cookie::__construct
	 *
	 * @dataProvider  provider_it_sets_config_options
	 */
	public function test_it_sets_config_options(array $config, array $expected)
	{
		$storage = new Cerberus_Storage_Cookie($config);

		$this->assertAttributeSame($expected['name'], 'name', $storage);
		$this->assertAttributeSame($expected['lifetime'], 'lifetime', $storage);
	}

	public function test_it_is_empty_initialy()
	{
		$storage = new Cerberus_Storage_Cookie;

		$this->assertTrue($storage->is_empty());
	}

	/**
	 * @covers  Cerberus_Storage_Cookie::is_empty
	 */
	public function test_it_is_not_empty_after_writing()
	{
		$storage = new Cerberus_Storage_Cookie;

		$storage->write('whatever');

		$this->assertFalse($storage->is_empty());
	}

	/**
	 * @covers  Cerberus_Storage_Cookie::clear
	 * @covers  Cerberus_Storage_Cookie::is_empty
	 */
	public function test_it_is_empty_after_clearing()
	{
		$storage = new Cerberus_Storage_Cookie;

		$storage->write('junk');

		$storage->clear();

		$this->assertTrue($storage->is_empty());
	}

	/**
	 * @covers  Cerberus_Storage_Cookie::read
	 * @covers  Cerberus_Storage_Cookie::write
	 */
	public function test_it_reads_and_writes_content()
	{
		$storage = new Cerberus_Storage_Cookie;

		$storage->write('foobar');

		$this->assertSame('foobar', $storage->read());
	}
}