<?php
require_once($CFG->dirroot . "/blocks/nlrsbook_auth/Query.php");

use App\Querys\Query;

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

        $setting = get_config('nlrsbook_auth', 'org_private_key'); // Секретный ключ организации
        $auth_msg = file_get_contents($CFG->dirroot . "/blocks/nlrsbook_auth/message/auth.php");
        $setting_msg = file_get_contents($CFG->dirroot . "/blocks/nlrsbook_auth/message/setting.php");

        if ($setting) {
            if (Query::getToken()) {
                $this->content->text = <<<HTML
                    <style>{$style}</style>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                    <div>
                        <div class="nlrsbook_shelf_grid" id="nlrsbook_shelf_list">
                        </div>
                    </div>
                    <script type="text/javascript">{$js}</script>
                HTML;
            } else {
                $this->content->text = $auth_msg;
            }
        } else {
            $this->content->text = $setting_msg;
        }

        return $this->content;
    }

    function has_config()
    {
        return true;
    }

}
