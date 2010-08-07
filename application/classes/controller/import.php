<?php

class Controller_Import extends Controller_Site
{
	/**
	 * @var string	Secret key to trigger importing of feeds
	 */
	private $_secret_key = 'd971fbda-5d99-4ef2-b5ed-2ec29e595716';
	
	/**
	 * @var string 		Currently activated user id
	 */
	private $_user_id = 1;
	
	public function action_index()
	{
		$this->auto_render = false;
		$secret = $this->request->param('id');
		
		if ($secret != $this->_secret_key)
		{
			exit;
		}

		// otherwise, process import
		$import_manager = new Import_Manager;
		try {
			$import_manager->batch_import();
			
			Kohana::$log->add(
				Kohana::DEBUG,
				'Succesfull import'
			);
		}
		catch (Exception $e)
		{
			Kohana::$log->add(
				Kohana::DEBUG,
				'Error while importing: ' . $e->getMessage()
			);
		}
		
		if ($import_manager->get_import_count() > 0)
		{
			// clear rss cache
			Model_Feed::clear_rss($this->_user_id);
			
			// clear page cache
			Dc_Pagecache::cleanup();
		}
	}
}