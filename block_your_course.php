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
		# If content's already defined, skip the code below...				
		if ($this->content !== null) 
		{
		  return $this->content;
		}
		# ...else get content from get_yc_content()
		global $CFG;	
		$cache = cache::make('block_your_course', 'yourcoursedata'); // call factory method for caching			
		# If running on localhost with XAMPP use a short cache TTL for testing...
		if($_SERVER['SERVER_NAME'] == 'localhost')
		{
			$cachettl = 5; // cache ttl seconds
		}
		else
		#...set the TTL to a minute
		{
			$cachettl = 5; // cache ttl seconds
		}

		
		// determine whether cache exists, needs refreshing
		# Timestamp is set in get_yc_content
		if ($cachetimestamp = $cache->get('yourcoursetimestamp'))
		{
			# Compare with current time. If the cache's time to live hasn't expired
			# then get the data currently in the cache
			if ((time() - $cachetimestamp) < $cachettl)
			{
				$content = $cache->get('yourcoursedata');
			}	
	
		}
	
		# If the cache has passed its expire by date, rebuild the block 
		# content and put that into the cache by calling function below. 
		if (empty ($content)){			
			// re-fetch content and rebuild cache
			$content = $this->get_yc_content($cache);
		}
		
		$this->content = new stdClass;
		$this -> content -> text = $content;		
		// $this->content->footer = 'Your Course footer';	   
		return $this->content;
    }

	/**
	* Enable global configuration of this block. That is, let Moodle
	* know to look for a settings.php file which the admin can use, 
	* in block admin, to enable a "Settings" link to options defined in 
	* settings.php
	*/
	function has_config() 
	{
		return true;
	}
	
	/**
	* Allow multiple instances of the block to be used in a course.
	* By default only one block instance is allowed. Although we're only allowing
	* a single instance, this is left in just in case
	*/
	public function instance_allow_multiple() 
	{
	  return false;
	}	
		
	private function get_yc_content($cache){
		global $CFG, $COURSE, $OUTPUT;
		
		$content = null;
		# If running on localhost with XAMPP try a test URL...

		if($_SERVER['SERVER_NAME'] == 'localhost')
		{
			$url = 'http://icitydelta.bcu.ac.uk/api/yourcourse/tee/48';
		}
		else
		#...get the course URL from the live system
		{
			$url = 'http://icitydelta.bcu.ac.uk/api/yourcourse/' . $CFG->facshort . '/' . $COURSE->id;
		}
		# Set HTTP headers to get XML back from Matt's script	
		$headers = array
		(
	        'Content-Type' => 'application/xml',
	        'Accept' => 'application/xml'
	    );		
		
		# Get the data using a Moodle function defined in /lib/filelib.php
		# Saves faffing about with CURL
		$data = download_file_content($url, $headers, null, false, '300', '20', true, null, false);
			
		if ($data)
		{
			# Parse the returned XML to extract useful data
			$module = simplexml_load_string($data);			
			# Put the various elements into variables for readability. Never mind 'code is beauty'.
			$leader = $module -> ModuleLeader[0] -> DisplayName;
			$leader_photo = $module -> ModuleLeader[0] -> PhotographUrl;
			$email = $module->ModuleLeader->Email;
			$tel = $module -> ModuleLeader -> Phone;
			$module_details_url = $module -> YourCourseModuleUrl;
			$module_guide_url = $module -> ModuleGuideUrl;	
			
			$assignurls = $module -> AssignmentBriefUrls;
			$assignments = '';
			$i = 0;
			
			# Get the URL of the official Moodle email icon using the OUTPUT API	
            $mail_icon = $OUTPUT->pix_url('i/email', 'core'); // Output an img tag pointing to the image

         	if (! empty($this -> config -> modulenotes)) 
			{            
            	$module_notes = $this -> config -> modulenotes;
			}
            # Assemble block text

            # Block configuration options:
            # MODULE LEADER
            # Details of module leader with mail icon
            $leader_details = "Module Leader: $leader<br />";
			# Display photo of the Dear Leader?
            if ($this -> config -> leaderphoto)
			{
				$leader_details .= "<img src=\"$leader_photo\" alt=\"Module leader photo\" title=\"Module leader photo\" align=\"middle\"><br />";
			}
			$leader_details .= "Tel: $tel &nbsp; <a href=\"mailto:$email?subject=$COURSE->fullname\">
								<img src=\"$mail_icon\" alt=\"Email the module leader\" title=\"Email the module leader \" /></a><br /><br />";
			
			# ASSIGNMENTS
            if ($this -> config -> assignments)
			{
				#  Add links to assignments
				foreach ($assignurls->string as $assignurl)
				{
					$i ++;
					$assignments .= "<a href='$assignurl'>Assignment " . $i . "</a><br />";
				}		
				rtrim($assignments, '<br />');
		
			}	
			else 
			{
				$assignments = "<br />";
			}
			
			$content = "<p style='text-align:center'> $leader_details
						<a href=\"$module_guide_url\" target=\"modulewin\">Module guide</a><br />
	        			<a href=\"$module_details_url\" target=\"modulewin\">Module details</a><br /><br />
	        			$assignments
				        $module_notes</p>";
			
			/*$content = "<p style='text-align:center'>" . "Module Leader:<br />
			<img src=\"$leader_photo\" alt=\"Module leader photo\" title=\"Module leader photo\" align=\"middle\">
			<br />$leader<br />		
			Tel: $tel &nbsp; <a href=\"mailto:$email?subject=$COURSE->fullname\"><img src=\"$mail_icon\" alt=\"Email the module leader\" title=\"Email the module leader \"/></a><br /><br />			
	        <a href=\"$module_guide_url\" target=\"modulewin\">Module guide</a><br />
	        <a href=\"$module_details_url\" target=\"modulewin\">Module details</a><br /><br />
	        $assignments<br />
	        $module_notes</p>
	        " ;		
			*/	
			
			// set data in cache
			$cache->set('yourcoursedata', $content);
			// set timestamp in cache to compare to ttl later
			$cache->set('yourcoursetimestamp', time());																	
		}
			return $content;
	}
	
		/**
	* Allow tweaks to the block title without having to upgrade or de- and re-install
	* See http://docs.moodle.org/dev/Blocks/Appendix_A#specialization.28.29
	* in the Blocks tutorial.
	* Function called by Moodle after init()
	
	*/
	public function specialization() 
	{
		# If the admin has edited optional fields (eg block name) then display that.
		if (!empty($this -> config -> title)) 
		{
			$this -> title = $this -> config -> title;
		} else 
		{ 
			$this -> config -> title = 'Your Course';
		}
	 
	  if (empty($this -> config -> text)) 
	  {
		$this->config->text = 'Default Your Course block text ...';
	  }    
	  
	  if (empty($this -> config -> modulenotes))
	  {
		$this -> config -> modulenotes = "Default module notes...";
	  }
	  

	}
} // end class