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

	/**
	 * Generate the content for the block, returning it in $this -> content.
	 * This is the standard approach in Moodle.
	 * Note that the content is actually defined in get_yc_content() where it's put
	 * into a cache. The get_content() function checks the cache content, and if the cache 
	 * is empty, or if its content is past its use-by time, get_yc_content() is called
	 * to refill the cache. 
	 * So, if you want to add more stuff to the content, do it in get_yc_content(). 
	 * @return string 
	 */
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
	
		# If the cache has passed its expire by time, rebuild the block 
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
	
	/**
	 * The main content-generating function. 
	 * This function creates the block content, shoves it into a cache, 
	 * then returns it to the calling get_content() function.
	 * If you want to add owt to the block content, do it here. 
	 * @return string
	 * 
	 */
	private function get_yc_content($cache){
		global $CFG, $COURSE, $OUTPUT;
		
		$content = null;
		# If running on localhost with XAMPP try a test URL...

		if($_SERVER['SERVER_NAME'] == 'localhost')
		{
			# Test URL which returns XML with correct headers sent
			$url = 'http://icitydelta.bcu.ac.uk/api/yourcourse/tee/48';
			# URL below doesn't return XML
			// $url = "http://icitydelta.bcu.ac.uk/api/yourcourse/biad/60";
			# URL below guaranteed to return valid and well-formed XML
			//$url =  "http://sonet.nottingham.ac.uk/rlos/rlo_rssfeed.php";
			
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
		# If a CURL error occurs, maybe down to an invalid URL, then return 
		# an empty string. This will effectively hide the Your Course block. 
		if (!$data)
		{
			return "";
		}
			
		if ($data)
		{
			# Parse the returned XML to extract useful data
			# If a XML error occurs, just return an empty string
			if (!$module = simplexml_load_string($data))
			{
				return "";
			}			
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