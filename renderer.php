<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * CUL Activity Stream renderer
 *
 * @package    block
 * @subpackage culactivity_stream
 * @copyright  2013 Amanda Doughty <amanda.doughty.1@city.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Main class for rendering the culactivity_stream block
 */
class block_bcu_your_course_renderer extends plugin_renderer_base {
        
    public function your_course_block($id, $module_info, $config) {
        $output = $this->module_information($module_info, $config);
        return $output;
    }

    public function your_course_modal($id, $module_info, $config) {
        $output = '';
        
        $output .= html_writer::start_tag('div', array( // 1
            'id' => $id,
            'class' => 'modal fade',
            'role' => 'dialog',
            'aria-labelledby' => 'myModalLabel',
            'aria-hidden' => 'true'
        ));  
        
        $output .= html_writer::start_tag('div', array( // 2
            'class' => 'modal-dialog'
        ));
        
        $output .= html_writer::start_tag('div', array( // 3
            'class' => 'modal-content'
        ));
        
        $output .= html_writer::start_tag('div', array( // 4
            'class' => 'modal-header'
        ));
        
        $output .= html_writer::start_tag('h4', array(
            'class' => 'modal-title'
        ));
        
        $output .= get_string('modalheading', 'block_bcu_your_course');
        
        $output .= html_writer::end_tag('h4');
        
        $output .= html_writer::end_tag('div'); // 4
        
        $output .= html_writer::start_tag('div', array( // 5
            'class' => 'modal-body'
        ));
        
        //$output .= $content;
        $output .= $this->module_information($module_info, $config);
        
        $output .= html_writer::end_tag('div'); // 5
        
        $output .= html_writer::start_tag('div', array(
            'class' => 'modal-footer'
        ));
        
        $output .= html_writer::start_tag('button', array(
            'type' => 'button',
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal'
        ));
        
        $output .= 'Close';
        
        $output .= html_writer::end_tag('button');
        
        $output .= html_writer::end_tag('div');
        
        $output .= html_writer::end_tag('div'); // 3
        
        $output .= html_writer::end_tag('div'); //2
        
        $output .= html_writer::end_tag('div'); //1
        
        $output .= html_writer::start_tag('div', array(
            'class' => 'text-center'
        ));
        
        $output .= html_writer::start_tag('button', array(
            'class' => 'btn btn-primary btn-lg',
            'data-toggle' => 'modal',
            'data-target' => '#'.$id
        ));       
        
        $output .= get_string('modalbuttontext', 'block_bcu_your_course');
        
        $output .= html_writer::end_tag('button');
        
        $output .= html_writer::end_tag('div');
        
        return $output;
    }

    public function module_information($module_info, $config)
    {
        $output = '';
        
        if(count($module_info->YourCourseModule)>0) {
            foreach($module_info->YourCourseModule as $module_leader) {
                $output .= html_writer::start_tag('p', array(
                    'style' => 'text-align: center'
                ));
        
                $output .= $this->process_module_leader($module_leader->ModuleLeader)."<br>";
            
                $output .= $this->process_module_assignments($module_leader->AssignmentBriefUrls);
                
                if(strlen($module_info->ModuleGuideUrl)>0)
                {
                    $output .= $this->module_guide($module_info->ModuleGuideUrl)."<br>";
                }
                
                if(strlen($module_leader->YourCourseModuleUrl)>0)
                {
                    $output .= $this->module_details($module_leader->YourCourseModuleUrl)."<br>";
                }
                
                $output .= html_writer::end_tag('p');                
            }
            
            $output .= $this->module_notes($config);
        } else {
            $output .= html_writer::start_tag('p', array(
                'style' => 'text-align: center'
            ));
            
            $output .= $this->process_module_leader($module_info->ModuleLeader)."<br>";
            $output .= $this->process_module_assignments($module_info->AssignmentBriefUrls);
            
            if(strlen($module_info->ModuleGuideUrl)>0)
            {
                $output .= $this->module_guide($module_info->ModuleGuideUrl)."<br>";
            }
            
            if(strlen($module_info->YourCourseModuleUrl)>0)
            {
                $output .= $this->module_details($module_info->YourCourseModuleUrl)."<br>";
            }
            
            $output .= html_writer::end_tag('p');
                
            $output .= $this->module_notes($config);
            
        }
        return $output;
    }
        
    public function process_module_leader($module_leader) {
        $output = '';
        
        if($module_leader->DisplayName)
        {
            $output .= $this->module_leader_name($module_leader->DisplayName)."<br>";   
        }
        
        if(strlen($module_leader->PhotographUrl)>0)
        {
            $output .= $this->module_leader_photo($module_leader->PhotographUrl)."<br>";
        }
        
        if($module_leader->Phone)
        {
            $output .= $this->module_leader_phone($module_leader->Phone);   
        }
        
        if($module_leader->Email)
        {
            $output .= $this->module_leader_email($module_leader->Email);
        }
        return $output;
    }
    
    public function process_module_assignments($assignments)
    {
        $output = '';
        $i=0;
        if($assignments)
        {
            foreach ($assignments->string as $assignurl)
            {
                $i ++;
                $output .= html_writer::link($assignurl, 'Assignment '.$i, array('target'=>'_blank')).'<br>';
            }
        }       
        rtrim($output, '<br />');
    
        return $output;
    }   
    
    public function module_leader_name($leader_name)
    {
        return 'Module Leader: '.$leader_name.'<br>';
    }
    
    public function module_leader_photo($leader_photo)
    {
        $output = html_writer::empty_tag('img', array(
            'src' => $leader_photo,
            'alt' => 'Module Leader Photo',
            'title' => 'Module Leader Photo',
        ));
        
        return $output;
    }
    
    public function module_leader_phone($leader_phone)
    {
        return 'Tel: '.$leader_phone."<br>";
    }
    
    public function module_leader_email($leader_email)
    {
        GLOBAL $OUTPUT, $COURSE;
        
        $content = 'Email: ';
        
        $content .= html_writer::start_tag('a', array(
            'href' => 'mailto:'.$leader_email.'?subject='.$COURSE->fullname
        ));
        $content .= html_writer::start_tag('img', array(
            'src' => $OUTPUT->pix_url('i/email', 'core'),
            'alt' => 'Email the module leader',
            'title' => 'Email the module leader'
        ));
        
        $content .= html_writer::end_tag('a');
        
        return $content;
    }
    
    public function module_guide($module_guide)
    {
        return html_writer::link($module_guide, 'Module Guide', array('target'=>'_blank'));   
    }
    
    public function module_details($module_details)
    {
        return html_writer::link($module_details, get_string('icitylink', 'block_bcu_your_course'), array('target'=>'_blank'));
    }
    
    public function module_notes($config)
    {
        if (! empty($config -> modulenotes)) 
        {
            $content = html_writer::start_tag('p', array(
                'class' => 'text-center'
            ));
            $content .= $config -> modulenotes;
            $content .= html_writer::end_tag('p'); 
            
            return $content;
        }    
    }
   
}