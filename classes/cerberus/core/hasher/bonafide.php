<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Bonafide hasher
 *
 * Implements a bridge design pattern between Cerberus and Bonafide
 *
 * @link  https://github.com/shadowhand/bonafide
 *
 * @package    Cerberus
 * @category   Hasher
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Core_Hasher_Bonafide implements Cerberus_Hasher {

	/**
	 * @var  Bonafide
	 */
	private $bonafide;

	/**
	 * Creates a new Bonafide hasher
	 *
	 * @return  void
	 */
	public function __construct(Bonafide $bonafide)
	{
		$this->bonafide = $bonafide;
	}

	/**
	 * Implements [Cerberus_Hasher::check]
	 *
	 * @see  Cerberus_Hasher::check
	 *
	 * @param   string  Password to check
	 * @param   string  Hash to check against
	 * @return  bool
	 */
	public function check($password, $hash)
	{
		return $this->bonafide->check($password, $hash);
	}
}