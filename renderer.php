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
 * Custom renderer for output of pages
 *
 * @package    mod_simplelesson
 * @copyright  2019 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://github.com/moodlehq/moodle-mod_collaborate
 * @see https://github.com/justinhunt/moodle-mod_collaborate
 */
use \mod_collaborate\local\debugging;
defined('MOODLE_INTERNAL') || die();

/**
 * Renderer for collaborate mod.
 */
class mod_collaborate_renderer extends plugin_renderer_base {

    /**
     * Displays the main view page content.
     *
     * @param $collaborate the collaborate instance std Object
     * @param $cm the course module std Object
     * @return none
     */
    public function render_view_page_content($collaborate, $cm) {

        $data = new stdClass();

        $data->heading = $collaborate->title;
        // Moodle handles processing of std intro field.
        $data->body = format_module_intro('collaborate',
                $collaborate, $cm->id);

         // Set up the user page URLs.
        $a = new \moodle_url('/mod/collaborate/showpage.php',
                ['cid' => $collaborate->id, 'page' => 'a']);
        $b = new \moodle_url('/mod/collaborate/showpage.php',
                ['cid' => $collaborate->id, 'page' => 'b']);
        $data->url_a = $a->out(false);
        $data->url_b = $b->out(false);

        // Display the view page content.
        echo $this->output->header();
        echo $this->render_from_template('mod_collaborate/view', $data);
        echo $this->output->footer();
    }
    /**
     * Displays the submissions page (showpage.php).
     *
     * @param object $collaborate the collaborate instance std Object
     * @param object $cm the course module std Object
     * @param string $page which partner's page to display
     * @param object $form the submission forms object
     * @return none
     */
     public function render_page_content($collaborate, $cm, $page) {

        $data = new stdClass();

        $data->heading = $collaborate->title;

        $data->user = 'User: '. strtoupper($page);

        debugging::logit('Module insatce: ', $collaborate);

        // Get the content from the database.
        $content = ($page == 'a') ? $collaborate->instructionsa : $collaborate->instructionsb;
        $data->body = $content;

        // Get a return url back to view page.
        $urlv = new \moodle_url('/mod/collaborate/view.php', ['id' => $cm->id]);
        $data->url_view = $urlv->out(false);

        // Display the show page content.
        echo $this->output->header();
        echo $this->render_from_template('mod_collaborate/show', $data);
        echo $this->output->footer();
    }
}