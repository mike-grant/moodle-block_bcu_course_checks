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
		global $CFG;
		$cache = cache::make('block_your_course', 'yourcoursedata'); // call factory method for caching
		$content = "";		
		$cachettl = 60; // cache ttl seconds
		
		// determine whether cache exists, needs refreshing
		if ($cachetimestamp = $cache->get('yourcoursetimestamp')){
			if ((time() - $cachetimestamp) < $cachettl){
				$content = $cache->get('yourcoursedata');
			}			
		}
		
		if ($content == ""){			
			// re-fetch content and rebuild cache
			$content = $this->get_yc_content($cache);
		}
		
		if ($this->content !== null) 
		{
		  return $this->content;
		}
	 
		$this->content = new stdClass;
		$this -> content -> text = $content;		
		$this->content->footer = 'Your Course';
	 
	   
		return $this->content;
    }

	private function get_yc_content($cache){
		global $CFG, $COURSE;
		
		$content = "";
		# If running on localhost try a test URL...
		# Get useful environment variables
		$server_name = $_SERVER['SERVER_NAME'];
		
		# XAMMP on localhost on PC 
		if($server_name == 'localhost')
		{
			$url = 'http://icitydelta.bcu.ac.uk/api/yourcourse/tee/48';

		}
		else
		{
			$url = 'http://icitydelta.bcu.ac.uk/api/yourcourse/' . $CFG->facshort . '/' . $COURSE->id;
		}
		# Set HTTP headers to get XML back			
		$headers = array(
	        'Content-Type' => 'application/xml',
	        'Accept' => 'application/xml'
	    );		
			
		# Use Moodle function (in filelib.php) to get XML data to save Curl faffery
		$data = download_file_content($url, $headers, null, false, '300', '20', true, null, false);
			
		if (!$data)
		{
			// replace with empty string after testing whcih will prevent block from shwoing at all
			$content = "It does not look like there is any YourCourse information for this module"; 
		}
		else 
		{
			# Parse the returned XML to extract course data and URLs	
			$module = simplexml_load_string($data);			
			
			$leader = $module -> ModuleLeader[0] -> DisplayName;
			$email = $module->ModuleLeader->Email;
			$tel = $module -> ModuleLeader -> Phone;
			$module_details_url = $module -> YourCourseModuleUrl;
			$module_guide_url = $module -> ModuleGuideUrl;			
			$content = "<h3>Module information</h3>" . "<p>Leader: <a href=\"mailto:$email\">$leader</a><br>" . "Tel: $tel<br>" . 
			"<a href=\"$module_details_url\" target=\"modulewin\">Module details</a><br>" . 
	        "<a href=\"$module_guide_url\" target=\"modulewin\">Module guide</a></p>" . 
	        "time generated = " . time();
			
			// set data in cache
			$cache->set('yourcoursedata', $content);
			// set timestamp in cache to compare to ttl later
			$cache->set('yourcoursetimestamp', time());																	
		}
			return $content;
	}
} // end class
