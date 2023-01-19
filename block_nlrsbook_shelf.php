<?php

require_once($CFG->dirroot . "/blocks/nlrsbook_auth/Query.php");

require_login();

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

        $seamlessAuthUserId = $USER->id; // Идентицикатор пользователя
        $seamlessAuthOrgId = 1; // Идентификатор организации

        $secret = get_config('nlrsbook_auth', 'org_private_key'); // Секретный ключ организации
        $seamlessAuthSignature = Query::generateServerApiRequestSignature([
            "orgId" => $seamlessAuthOrgId,
            "userIdInEduPlatform" => $seamlessAuthUserId,
        ], $secret);

        $getToken = Query::getToken($seamlessAuthUserId, $seamlessAuthSignature); // TODO: получать из токена
        $nlrsUserId = Query::getSub($seamlessAuthUserId); // TODO: получать из токена

        $seamlessAuthSignatureBase64 = Query::generateServerApiRequestSignatureBase64([
            "orgId" => $seamlessAuthOrgId,
            "userIdInEduPlatform" => $nlrsUserId,
        ], $secret);

        $baseUrl = "https://e.nlrs.ru/seamless-auth-redirect?seamlessAuthOrgId=${seamlessAuthOrgId}&seamlessAuthUserId=${nlrsUserId}&seamlessAuthSignature=${seamlessAuthSignatureBase64}";

        $shelfUrl = "${baseUrl}&override_redirect=https%3A%2F%2Fnlrs.ru%2Flk%2Fshelf";
        $ordersShelfUrl = "${baseUrl}&override_redirect=https%3A%2F%2Fnew.nlrs.ru%2Flk%2Forders-shelf";
        $ticketsUrl = "${baseUrl}&override_redirect=https%3A%2F%2Fnlrs.ru%2Flk%2Ftickets";

        $this->content = new stdClass;

        $this->content->text = <<<HTML
            <style>{$style}</style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <div>
                <div class="mb-3">
                    <a href="{$shelfUrl}" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-sm mb-1">Моя полка</a>
                    <a href="{$ordersShelfUrl}" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-sm mb-1">Мои заказы</a>
                    <a href="{$ticketsUrl}" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-sm mb-1">Задать вопрос</a>
                </div>
                <h4 class="mb-3">Моя полка <span id="nlrsbook_shelf_count"></span></h4>
                <div class="nlrsbook_shelf_grid row" id="nlrsbook_shelf_list">
                </div>
            </div>
            <script type="text/javascript">{$js}</script>
        HTML;

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
