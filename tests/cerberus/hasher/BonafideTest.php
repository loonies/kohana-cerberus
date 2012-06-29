<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus_Hasher_Bonafide test
 *
 * @group  cerberus
 * @group  cerberus.hasher
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Hasher_BonafideTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers  Cerberus_Hasher_Bonafide::check
	 */
	public function test_it_checks_password()
	{
		if ( ! class_exists('Bonafide'))
		{
			$this->markTestSkipped('Test requres Bonafide module');
		}

		$bonafide = $this->getMockBuilder('Bonafide')
			->disableOriginalConstructor()
			->setMethods(array('check'))
			->getMock();

		$bonafide
			->expects($this->once())
			->method('check')
			->with($this->equalTo('pass'), $this->equalTo('hash'))
			->will($this->returnValue(TRUE));

		$hasher = new Cerberus_Hasher_Bonafide($bonafide);

		$this->assertTrue($hasher->check('pass', 'hash'));
	}
}
