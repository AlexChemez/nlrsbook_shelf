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

        $nlrsUserId = 48059; // TODO: получать из токена
        $seamlessAuthSignature = 'y3Mz2ahGpv7GMLGttHZ7PBTsfDaHtmPX'; // TODO: реализовать генерацию подписи, пока стоит временная заглушка
        $baseUrl = "https://e.nlrs.ru/seamless-auth-redirect?seamlessAuthUserId=${nlrsUserId}&seamlessAuthSignature=${seamlessAuthSignature}";

        // $shelfUrl = "${baseUrl}&override_redirect=https%3A%2F%2Fnlrs.ru%2Flk%2Fshelf";
        // $ordersShelfUrl = "${baseUrl}&override_redirect=https%3A%2F%2Fnew.nlrs.ru%2Flk%2Forders-shelf";
        // $ticketsUrl = "${baseUrl}&override_redirect=https%3A%2F%2Fnlrs.ru%2Flk%2Ftickets";

        $shelfUrl = "${baseUrl}&override_redirect=http%3A%2F%2Flocalhost:3000%2Flk%2Fshelf";
        $ordersShelfUrl = "${baseUrl}&override_redirect=http%3A%2F%2Flocalhost:3000%2Flk%2Forders-shelf";
        $ticketsUrl = "${baseUrl}&override_redirect=http%3A%2F%2Flocalhost:3000%2Flk%2Ftickets";

        $this->content = new stdClass;
        $this->content->text .= "<style>" . $style . "</style>";
        $this->content->text .= "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>";
        $this->content->text .= <<<HTML
            <div>
                <div class="mb-3">
                    <a href="{$shelfUrl}" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-sm mb-1">Моя полка</a>
                    <a href="{$ordersShelfUrl}" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-sm mb-1">Мои заказы</a>
                    <a href="{$ticketsUrl}" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-sm mb-1">Задать вопрос</a>
                </div>
                <h4 class="mb-3">Моя полка</h4>
                <div class="nlrsbook_shelf_grid row" id="nlrsbook_shelf_list">
                </div>
            </div>
HTML;
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