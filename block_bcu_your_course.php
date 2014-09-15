<?php


/**
Main file for the Your Course block. As reference, see blocks tutorial at:
http://docs.moodle.org/dev/Blocks

*/
class block_bcu_your_course extends block_base
{
	public function init()
	{
		 
		$this -> title = get_string('yourcourse', 'block_bcu_your_course');	

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
        GLOBAL $USER, $COURSE;
		# If content's already defined, skip the code below...				
		if ($this->content !== null) 
		{
		  return $this->content;
		}
		# ...else get content from get_yc_content()
		global $CFG;	
		$cache = cache::make('block_bcu_your_course', 'yourcoursedata'); // call factory method for caching			
		# If running on localhost with XAMPP use a short cache TTL for testing...
		if($_SERVER['SERVER_NAME'] == 'localhost')
		{
			$cachettl = 86400; // cache ttl seconds
		}
		else
		#...set the TTL to a minute
		{
			$cachettl = 86400; // cache ttl seconds
		}

		// determine whether cache exists, needs refreshing
		# Timestamp is set in get_yc_content
		if ($cachetimestamp = $cache->get('yourcoursetimestamp_'.$USER->id.'_'. $COURSE->id))
		{
			# Compare with current time. If the cache's time to live hasn't expired
			# then get the data currently in the cache
			if ((time() - $cachetimestamp) < $cachettl)
			{
				$content = $cache->get('yourcoursedata_'.$USER->id.'_'. $COURSE->id);
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
	private function get_yc_content($cache, $timestamp=false){
		global $CFG, $COURSE, $OUTPUT, $USER;
		
		$content = null;
		# If running on localhost with XAMPP try a test URL...
		if($_SERVER['SERVER_NAME'] != 'localhost')
        {
    		if(preg_match('^s\d+^', $USER->username)) {
    		    // Detect if the current user is a student
    		    $url = 'https://icity.bcu.ac.uk/API/CoursePortal/Get?courseId='.$COURSE->id.'&studNum='.substr($USER->username, 1);
    		}
            else 
            {
                $url = 'https://icity.bcu.ac.uk/API/CoursePortal/Get?courseId='.$COURSE->id;
            }		
        }
        else
        {
        	$url = 'https://icity.bcu.ac.uk/API/CoursePortal/Get?courseId=4443';
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
            
            $renderer = $this->page->get_renderer('block_bcu_your_course');
            if($timestamp)
            {
                $this->config->displayuntil = $timestamp;
            }
            if(!empty($this->config->displayuntil))
            {
                if($this->config->displayuntil > time())
                {
                    $content = $renderer->your_course_block('testmodal', $module, $this->config);
                } else {
                    $content = $renderer->your_course_modal('testmodal', $module, $this->config);
                }
            }
            else
            {
                $content = $renderer->your_course_modal('testmodal', $module, $this->config);
            }
            
            $content .= "<p>".time()."</p>";
            
            // set data in cache
            $cache->set('yourcoursedata_'.$USER->id.'_'. $COURSE->id, $content);
            // set timestamp in cache to compare to ttl later
            $cache->set('yourcoursetimestamp_'.$USER->id.'_'. $COURSE->id, time()); 															
		}
		return $content;
	}

    public function instance_config_save($data, $nolongerused = false)
    {
        $cache = cache::make('block_bcu_your_course', 'yourcoursedata'); // call factory method for caching
        $this->get_yc_content($cache, $data->displayuntil);
        return parent::instance_config_save($data);
    }
}