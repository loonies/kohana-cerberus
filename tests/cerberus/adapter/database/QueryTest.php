<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus_Adapter_Database_Query test
 *
 * @group  cerberus
 * @group  cerberus.adapter
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Adapter_Database_QueryTest extends PHPUnit_Framework_TestCase {

	/**
	 * Provider for [test_it_sets_config_options]
	 *
	 * @return  array
	 */
	public function provider_it_sets_config_options()
	{
		return array(
			array(array(
				'table'   => 'tbl',
				'columns' => array('identity' => 'eml', 'password' => 'passwd')
			)),
			array(array(
				'columns' => array('identity' => 'usr', 'password' => 'pass')
			)),
			array(array(
				'table'   => 'tab',
			)),
		);
	}

	/**
	 * @covers  Cerberus_Adapter_Database_Query::__construct
	 *
	 * @dataProvider  provider_it_sets_config_options
	 *
	 * @param   array   Configuration option
	 */
	public function test_it_sets_config_options(array $config)
	{
		$query = $this->getMockBuilder('Cerberus_Adapter_Database_Query')
			->setConstructorArgs(array($config))
			->getMockForAbstractClass();

		$default_table   = 'user';
		$default_columns = array(
			'identity' => 'username',
			'password' => 'password',
		);

		$this->assertAttributeSame(Arr::get($config, 'table', $default_table), 'table', $query);
		$this->assertAttributeSame(Arr::get($config, 'columns', $default_columns), 'columns', $query);
	}
}
