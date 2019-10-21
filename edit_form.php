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
 * Form for editing multiblock parent block instances.
 *
 * @package   block_multiblock
 * @copyright 2019 Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Form for editing multiblock parent block instances.
 *
 * @package   block_multiblock
 * @copyright 2019 Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_multiblock_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        // Let's have some configuration!
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // First, the block's title.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_multiblock'));
        $mform->setType('config_title', PARAM_TEXT);

        // Then which presentation format we want to use.
        $presentations = block_multiblock::get_valid_presentations();
        $options = [];
        foreach ($presentations as $presentationid => $presentation) {
            $options[$presentationid] = $presentation['name'];
        }
        $mform->addElement('select', 'config_presentation', get_string('presentation', 'block_multiblock'), $options);
        $mform->setDefault('config_presentation', block_multiblock::get_default_presentation());
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $presentations = block_multiblock::get_valid_presentations();
        if (isset($presentations[$data['config_presentation']])) {
            $selectedpresentation = $presentations[$data['config_presentation']];
            if (!empty($selectedpresentation['requires_title']) && empty($data['config_title'])) {
                $errors['config_presentation'] = get_string('requirestitle', 'block_multiblock', $selectedpresentation['name']);
            }
        }

        return $errors;
    }
}
