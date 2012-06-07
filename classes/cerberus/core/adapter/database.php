<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Database authentication adapter
 *
 * @package    Cerberus
 * @category   Adapter
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Core_Adapter_Database implements Cerberus_Adapter {

	/**
	 * @var  Cerberus_Adapter_Database_Query
	 */
	protected $query;

	/**
	 * @var  Cerberus_Hasher
	 */
	protected $hasher;

	/**
	 * @var  string  Identity
	 */
	protected $identity = NULL;

	/**
	 * @var  string  Password
	 */
	protected $password = NULL;

	/**
	 * Creates a new database adapter instance
	 *
	 * @param   Cerberus_Adapter_Database_Query
	 * @param   Cerberus_Hasher
	 * @return  void
	 */
	public function __construct(
		Cerberus_Adapter_Database_Query $query,
		Cerberus_Hasher $hasher
	)
	{
		$this->query  = $query;
		$this->hasher = $hasher;
	}

	/**
	 * Set credentials
	 *
	 * @param   string  Identity
	 * @param   string  Password
	 * @return  Cerberus_Adapter_Database
	 */
	public function credentials($identity, $password)
	{
		$this->identity = $identity;

		$this->password = $password;

		return $this;
	}

	/**
	 * Implements [Cerberus_Adapter::authenticate]
	 *
	 * @return  Cerberus_Result
	 */
	public function authenticate()
	{
		$hash = $this->query->find($this->identity);

		$password_check = $this->hasher->check($this->password, $hash);

		if ($hash === NULL)
		{
			return new Cerberus_Result(Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND, $this->identity);
		}

		if ($password_check === FALSE)
		{
			return new Cerberus_Result(Cerberus_Result::FAILURE_CREDENTIAL_INVALID, $this->identity);
		}
		elseif ($password_check === TRUE)
		{
			return new Cerberus_Result(Cerberus_Result::SUCCESS, $this->identity);
		}

		return new Cerberus_Result(Cerberus_Result::FAILURE_GENERAL, $this->identity);
	}
}