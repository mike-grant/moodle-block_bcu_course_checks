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
        
    public function your_course_block($id, $content, $module_info) {
        $output = '';
        
        //$output .= $content;
        $output .= $this->module_information($module_info);
        
        return $output;
    }

    public function your_course_modal($id, $content, $module_info) {
        $output = '';
        
        $output .= html_writer::start_tag('div', array( // 1
            'id' => $id,
            'class' => 'modal fade',
            'role' => 'dialog',
            'aria-labelledby' => 'myModalLabel',
            'aria-hidden' => FALSE
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
        $output .= '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'; // Update this to use html_writer content
        $output .= get_string('modalheading', 'block_bcu_your_course');
        
        $output .= html_writer::end_tag('h4');
        
        $output .= html_writer::end_tag('div'); // 4
        
        $output .= html_writer::start_tag('div', array( // 5
            'class' => 'modal-body'
        ));
        
        //$output .= $content;
        $output .= $this->module_information($module_info);
        
        $output .= html_writer::end_tag('div'); // 5
        
        $output .= html_writer::end_tag('div'); // 3
        
        $output .= html_writer::end_tag('div'); //2
        
        $output .= html_writer::end_tag('div'); //1
        
        $output .= html_writer::start_tag('button', array(
            'class' => 'btn btn-primary btn-lg',
            'data-toggle' => 'modal',
            'data-target' => '#'.$id
        ));       
        
        $output .= 'test modal button';
        
        $output .= html_writer::end_tag('button');
        
        return $output;
    }

    public function module_information($module_info)
    {
        $output = '';
        if(is_array($module_info->YourCourseModule)) {
            
            foreach($module_info->YourCourseModule as $module_leader) {
                
                $output .= html_writer::start_tag('p', array(
                    'style' => 'text-align: center'
                ));
        
                $output .= $this->process_module_leader($module_leader);
                
                $output .= html_writer::end_tag('p');
                
            }
            
        } else {
            
            $output .= html_writer::start_tag('p', array(
                'style' => 'text-align: center'
            ));
            
            $output .= $this->process_module_leader($module_info->ModuleLeader);
            
            $output .= html_writer::end_tag('p');
            
        }
        return $output;
    }
        
    public function process_module_leader($module_leader) {
        $output = '';
        
        echo "<pre>"; print_r($module_leader); echo "</pre>";
        
        if($module_leader->DisplayName)
        {
            $output .= $this->module_leader_name($module_leader->DisplayName)."<br>";   
        }
        
        if($module_leader->PhotographUrl)
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
    
    public function module_leader_name($leader_name)
    {
        return 'Module Leader: '.$leader_name.'<br>';
    }
    
    public function module_leader_photo($leader_photo)
    {
        $output = html_writer::start_tag('img', array(
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
        GLOBAL $OUTPUT;
        
        $content = html_writer::start_tag('a', array(
            'href' => 'mailto:'.$leader_email
        ));
        $content .= html_writer::start_tag('img', array(
            'src' => $OUTPUT->pix_url('i/email', 'core'),
            'alt' => 'Email the module leader',
            'title' => 'Email the module leader'
        ));
        
        $content .= html_writer::end_tag('a');
        
        //$leader_details .= "Tel: $tel &nbsp; <a href=\"mailto:$email?subject=$COURSE->fullname\"><img src=\"$mail_icon\" alt=\"Email the module leader\" title=\"Email the module leader \" /></a><br /><br />";
        return $content;
    }
}