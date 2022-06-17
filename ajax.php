<?php
require(__DIR__ . '/Query.php');
use App\Services\Query;

define('AJAX_SCRIPT', true);
require_once('../../config.php');

require_login();

$user_id = $USER->id;

$first = 6;
$page = optional_param('page', 1, PARAM_INT);
$remove = optional_param('remove', null, PARAM_INT);
$token = Query::getToken($user_id);

$nlrsUserId = 48059; // TODO: получать из токена
$seamlessAuthSignature = 'y3Mz2ahGpv7GMLGttHZ7PBTsfDaHtmPX'; // TODO: реализовать генерацию подписи, пока стоит временная заглушка
$baseUrl = "https://e.nlrs.ru/seamless-auth-redirect?seamlessAuthUserId=${nlrsUserId}&seamlessAuthSignature=${seamlessAuthSignature}";

$myShelf = Query::getShelf($page, $first, $token);

$myShelfBooks = $myShelf['data'];
$myShelfPagi = $myShelf['paginatorInfo'];

$removeBook = Query::removeBookToShelf($remove, $token);

$count = $myShelfPagi['total'];

if ($myShelfBooks) {
foreach ($myShelfBooks as $key => $book) {
    $bookUrl = "${baseUrl}&override_redirect=/online2/".$book['id'];
    $content .= '<div class="nlrsbook_shelf_card col-6 col-sm-4 col-md-2" data-id="' . $book['id'] . '">
                    <div class="nlrsbook_shelf_card__img_wrapper">
                        <div class="nlrsbook_shelf_card__img_responsive"></div>
                        <img src="'.$book['cover_thumb_url'].'" class="nlrsbook_shelf_card__img">
                        <div class="nlrsbook_shelf_card__dropdown dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                        </div>
                        <ul class="dropdown-menu">
                            <li><a data-remove="'.$book['id'].'" class="nlrsbook-remove dropdown-item">Убрать из полки</a></li>
                        </ul>
                    </div>
                    <a target="_blank" href="'.$bookUrl.'" target="_blank" class="nlrsbook_shelf_card__btn btn btn-primary btn-block btn-sm mt-2">Читать</a>
                    <div class="nlrsbook_shelf_card__title mt-1">'.$book['title'].'</div>
                </div>';
}
$content .= pagination($count, $first, $page);
} else {
    $content .= '<div class="col-12 col-sm-12 col-md-12"><div class="alert alert-info">В вашей полке нет книг</div></div>';
}

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
            if ($page == $pages)
                $output .= "<li class=\"page-item active\"><a data-page=\"" . ($pages) . "\" class=\"page-link nlrsbook-page\" >" . $pages . "</a ></li > ";
            else
                $output .= "<li class=\"page-item \"><a data-page=\"" . ($pages) . "\" class=\"page-link nlrsbook-page\">" . $pages . "</a></li>";
        }

        if ($page < $pages) {
            $output .= "<li class=\"page-item\"><a data-page=\"" . $page + 1 . "\" class=\"page-link nlrsbook-page\"><span>»</span></a></li>";
        } else {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link nlrsbook-page\">»</span></li>";
        }

    }

    $output .= "</ul></div>";
    return $output;
}

echo json_encode(['page' => $page, 'remove' => $remove, 'html' => $content]);