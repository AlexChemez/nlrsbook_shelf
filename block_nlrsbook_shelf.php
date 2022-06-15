<?php

class block_nlrsbook_shelf extends block_base {

    public function init() {
        $this->title = get_string('nlrsbook_shelf', 'block_nlrsbook_shelf');
    }

    public function get_content() {
        global $CFG;
        if ($this->content !== null) {
            return $this->content;
        }

        $style = file_get_contents($CFG->dirroot . "/blocks/nlrsbook_shelf/style/nlrsbook_shelf.css");
        $js = file_get_contents($CFG->dirroot . "/blocks/nlrsbook_shelf/js/nlrsbook_shelf.js");
        $mainPage = file_get_contents($CFG->dirroot . "/blocks/nlrsbook_shelf/templates/rendermainpage.moustache");

        $this->content = new stdClass;
        $this->content->text .= "<style>" . $style . "</style>";
        $this->content->text .= "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>";
        $this->content->text .= $mainPage;
        $this->content->text .= "<script type=\"text/javascript\"> " . $js . " </script>";

        return $this->content;
    }
    
/*
    public function hide_header()
    {
        return true;
    }
*/

    function has_config()
    {
        return true;
    }

}