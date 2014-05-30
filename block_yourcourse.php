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
		# Matt's test url
		$url = 'http://www.fredriley.org.uk/test/48.xml';
		# Let's try to get XML data from the URL using the Moodle func download_file_content() defined in lib/filelib.php
		$data = download_file_content($url, null, null, false, '300', '20', true, null, false);
		if (!$data)
		{
			$content = "Unable to retrieve your course data: bummer";
		}
		else 
		{
			# Let's parse this XML fecker!
			//$content = "Your course data retrieved ok";
			# PHP manual: "simplexml_load_string — Interprets a string of XML into an object "
			$module = simplexml_load_string($data);
			# Use object notation to get module details
			$leader = $module -> ModuleLeader[0] -> DisplayName;
			$email = $module -> ModuleLeader -> Email;
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
	 
		$this->content         =  new stdClass;
		$this -> content -> text = $content;
		//$this->content->text   = 'Your Course: some content';
		$this->content->footer = 'Your Course: footer';
	 
		return $this->content;
	}
	


} // end class








?>