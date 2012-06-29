<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Rapiddm query
 *
 * Implements a bridge design pattern between Cerberus and Rapiddm
 *
 * @link  https://github.com/loonies/kohana-rapiddm
 *
 * @package    Cerberus
 * @category   Adapter/Database
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Core_Adapter_Database_Query_Rapiddm
	extends Cerberus_Adapter_Database_Query {

	/**
	 * @var  Db_Connection
	 */
	private $db;

	/**
	 * Creates a new Cerberus Rapiddm query
	 *
	 * @param   array   Config options
	 * @param   Db_Connection
	 * @return  void
	 */
	public function __construct(array $config = array(), Db_Connection $db)
	{
		parent::__construct($config);

		$this->db = $db;
	}

	/**
	 * Implements [Cerberus_Adapter_Database_Query::find]
	 *
	 * @see  Cerberus_Adapter_Database_Query::find
	 *
	 * @param   string  Identity to search for
	 * @return  string  Password of identity or NULL in case identity not found
	 */
	public function find($identity)
	{
		$result = $this->db->select()
			->from($this->table, $this->columns['password'])
			->where($this->columns['identity'], $identity)
			->limit(1)
			->get()
			->val();

		return ($result === FALSE) ? NULL : $result;
	}
}
