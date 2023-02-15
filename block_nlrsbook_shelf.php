<?php

class block_nlrsbook_shelf extends block_base {

    public function init() {
        $this->title = get_string('nlrsbook_shelf', 'block_nlrsbook_shelf');
    }

    public function get_content() {
        global $CFG, $USER;
        if ($this->content !== null) {
            return $this->content;
        }

        $style = file_get_contents($CFG->dirroot . "/blocks/nlrsbook_shelf/style/nlrsbook_shelf.css");
        $js = file_get_contents($CFG->dirroot . "/blocks/nlrsbook_shelf/js/nlrsbook_shelf.js");

        $this->content = new stdClass;

        $this->content->text = <<<HTML
            <style>{$style}</style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <div>
                <div class="nlrsbook_shelf_grid" id="nlrsbook_shelf_list">
                </div>
            </div>
            <script type="text/javascript">{$js}</script>
        HTML;

        return $this->content;
    }

    function has_config()
    {
        return true;
    }

}
