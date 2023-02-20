<?php

define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../config.php');

require_once($CFG->dirroot . "/blocks/nlrsbook_auth/Query.php");

require_login();

use App\Querys\Query;

global $USER;

$first = 6; // Количество книг на страницу
$page = optional_param('page', 1, PARAM_INT); // get запрос на получение номера страницы для пагинатора
$remove = optional_param('remove', null, PARAM_INT); // get запрос на получение идентификатора книги для удаления с полки*/

$removeBook = Query::removeBookToShelf($remove); // функия удаления книги с полки

$getShelf = Query::getShelf($page, $first); // получение полки пользователя

$myShelfBooks = $getShelf['data']; // Подучение данных полки читателя
$count = $getShelf['paginatorInfo']['total']; // Получение количества книг в полке читателя

if ($myShelfBooks) {
    foreach ($myShelfBooks as $key => $book) {
        $bookUrl = Query::getUrl("online2/".$book['id']);
        $content .= '<div class="nlrsbook_shelf_card">
                            <div class="nlrsbook_shelf_card__img_wrapper">
                                <div class="nlrsbook_shelf_card__img_responsive"></div>
                                <img src="'.$book['cover_thumb_url'].'" class="nlrsbook_shelf_card__img">
                                <div class="nlrsbook_shelf_card__dropdown dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a data-remove="'.$book['id'].'" class="nlrsbook-remove dropdown-item"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Убрать из полки</a></li>
                                </ul>
                            </div>
                            <div class="nlrsbook_shelf_card_wrapper">
                                <div class="order_title">
                                    <a target="_blank" href="'.$bookUrl.'" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-block btn-sm">Читать</a>
                                </div>
                                <div class="nlrsbook_shelf_card__title">'.$book['title'].'</div>
                            </div>
                        </div>';
    }
    $content .= pagination($count, $first, $page);
} else {
    $content .= '<div class="col-12 col-sm-12 col-md-12"><div class="alert alert-info">В вашей полке нет книг</div></div>';
}

echo json_encode(['page' => $page, 'html' => $content]);

// Переключатель страниц
function pagination($count, $first, $page)
{
    $output .= "<div class=\"nlrsbook_shelf_pagination col-12\"><ul class=\"pagination pagination-sm\">";
    $pages = ceil($count / $first);

    if ($pages > 1) {

        if ($page > 1) {
            $output .= "<li class=\"page-item\"><a data-page=\"" . ($page - 1) . "\" class=\"page-link nlrsbook-page\" ><span>«</span></a></li>";
        } else {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link nlrsbook-page\">«</span></li>";
        }

        if (($page - 3) > 0) {
            $output .= "<li class=\"page-item \"><a data-page=\"1\" class=\"page-link nlrsbook-page\">1</a></li>";
        }
        if (($page - 3) > 1) {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link nlrsbook-page\">...</span></li>";
        }

        for ($i = ($page - 2); $i <= ($page + 2); $i++) {
            if ($i < 1) continue;
            if ($i > $pages) break;
            if ($page == $i)
                $output .= "<li class=\"page-item active\"><a data-page=\"" . ($i) . "\" class=\"page-link nlrsbook-page\" >" . $i . "</a ></li > ";
            else
                $output .= "<li class=\"page-item \"><a data-page=\"" . ($i) . "\" class=\"page-link nlrsbook-page\">" . $i . "</a></li>";
        }

        if (($pages - ($page + 2)) > 1) {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link nlrsbook-page\">...</span></li>";
        }
        if (($pages - ($page + 2)) > 0) {
            if ($page == $pages) {
                $output .= "<li class=\"page-item active\"><a data-page=\"" . ($pages) . "\" class=\"page-link nlrsbook-page\" >" . $pages . "</a ></li > ";
            } else {
                $output .= "<li class=\"page-item \"><a data-page=\"" . ($pages) . "\" class=\"page-link nlrsbook-page\">" . $pages . "</a></li>";
            }
        }

        if ($page < $pages) {
            $output .= "<li class=\"page-item\"><a data-page=\"" . ($page + 1) . "\" class=\"page-link nlrsbook-page\" ><span>»</span></a></li>";
        } else {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link nlrsbook-page\">«</span></li>";
        }

    }

    $output .= "</ul></div>";
    return $output;
}