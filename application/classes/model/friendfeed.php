<?php defined('SYSPATH') or die('No direct script access.');

class Model_Friendfeed extends Sprig
{
	const USERS_PER_IMPORT = 3;
	
	/**
	 * @var string
	 */
	protected $_table = 'dc_ff2fb_friendfeed';
	
	/**
	 * @var string
	 */
	protected $_primary_key = 'user_id';
	
	/**
	 * @var int
	 */
	protected $_total_count;
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'user_id'		=> new Sprig_Field_BelongsTo(array(
				'model'			=> 'User',
				'column'		=> 'user_id',
				'unique'		=> TRUE
			)),
			'friendfeed_id'	=> new Sprig_Field_Char(array(
				'unique'		=> TRUE
			))
		);
	}
	
	/**
	 * Returns the total number of user accounts
	 *
	 * @return int
	 */
	public function get_total()
	{
		if ($this->_total_count === null)
		{
			$this->_total_count = $this->count();
		}
		
		return $this->_total_count;
	}
	
	/**
	 * Returns the total number of batch
	 * for a given total record count
	 *
	 * @param int $total_rec
	 * @return int
	 */
	public function get_total_batch($total_rec)
	{
		$ret = ceil($total_rec / self::USERS_PER_IMPORT);
		if ($ret > 0)
		{
			return (int)$ret;
		}
		return 1;
	}
	
	/**
	 * Returns a paginated list of user accounts
	 * along with comments
	 *
	 * @param int $batch
	 * @return array
	 */
	public function get_batch($batch)
	{
		$batch = (int)$batch;
		
		$total_rec = $this->get_total();
		$total_batch = $this->get_total_batch($total_rec);
		
		if ($batch < 1)
		{
			$batch = 1;
		}
		if ($batch > $total_batch)
		{
			$batch = $total_batch;
		}
		
		$offset = ($batch - 1) * self::USERS_PER_IMPORT;
		
		$results = DB::select()->from($this->_table)
			->limit(self::USERS_PER_IMPORT)
			->offset($offset)
			->execute();
			
		return $results->as_array();
	}
}