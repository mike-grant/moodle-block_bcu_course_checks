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



    public function your_course_modal($id, $content) {
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
        $output .= 'Modal Title';
        
        $output .= html_writer::end_tag('h4');
        
        $output .= html_writer::end_tag('div'); // 4
        
        $output .= html_writer::start_tag('div', array( // 5
            'class' => 'modal-body'
        ));
        
        $output .= $content;
        
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
}