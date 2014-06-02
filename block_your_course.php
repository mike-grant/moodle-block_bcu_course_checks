<?php


/**
Main file for the Your Course block. As reference, see blocks tutorial at:
http://docs.moodle.org/dev/Blocks

*/
class block_your_course extends block_base
{
	public function init()
	{		 
		$this -> title = get_string('yourcourse', 'block_your_course');	
	}

	public function get_content() 
	{
		global $CFG, $COURSE;
			
		$url = 'http://icitydelta.bcu.ac.uk/api/yourcourse/' . $CFG->facshort . '/' . $COURSE->id;
			
		$headers = array(
            'Content-Type' => 'application/xml',
            'Accept' => 'application/xml'
        );		
		
		$data = download_file_content($url, $headers, null, false, '300', '20', true, null, false);
		
		if (!$data)
		{
			// replace with empty string after testing whcih will prevent block from shwoing at all
			$content = "It does not look like there is any YourCourse information for this module"; 
		}
		else 
		{					
			$module = simplexml_load_string($data);			
			
			$leader = $module -> ModuleLeader[0] -> DisplayName;
			$email = $module->ModuleLeader->Email;
			$tel = $module -> ModuleLeader -> Phone;
			$module_details_url = $module -> YourCourseModuleUrl;
			$module_guide_url = $module -> ModuleGuideUrl;			
			$content = "<h3>Module information</h3>" . "<p>Leader: <a href=\"mailto:$email\">$leader</a><br>" . "Tel: $tel<br>" . 
				"<a href=\"$module_details_url\" target=\"modulewin\">Module details</a><br>" . 
				"<a href=\"$module_guide_url\" target=\"modulewin\">Module guide</a></p>";			
		}
		
		if ($this->content !== null) 
		{
		  return $this->content;
		}
	 
		$this->content = new stdClass;
		$this -> content -> text = $content;		
		$this->content->footer = 'Your Course: footer';
	 
		return $this->content;
    }
} // end class
