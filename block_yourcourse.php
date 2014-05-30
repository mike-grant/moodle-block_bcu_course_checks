<?php


/**
Main file for the Your Course block. As reference, see blocks tutorial at:
http://docs.moodle.org/dev/Blocks

*/
class block_yourcourse extends block_base
{
	public function init()
	{
		# Get title defined in language file lang/en/block_yourcourse.php 
		$this -> title = get_string('yourcourse', 'block_yourcourse');
	
	
	}

	public function get_content() 
	{
		if ($this->content !== null) 
		{
		  return $this->content;
		}
	 
		$this->content         =  new stdClass;
		$this->content->text   = 'Your Course: content';
		$this->content->footer = 'Your Course: footer';
	 
		return $this->content;
	}
	


} // end class








?>