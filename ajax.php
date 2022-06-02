<?php
define('AJAX_SCRIPT', true);
require_once('../../config.php');

require_login();
$page = optional_param('page', 1, PARAM_INT);

$url = file_get_contents('https://jsonplaceholder.typicode.com/photos?albumId='.$page);
$array = json_decode($url, true);
$count = 5000;

/*$orgToken = get_config('nlrsbook_shelf', 'org_token');*/

$content .= pagination($count, $page);
foreach ($array as $key => $value) {
  $content .= "<div class=\"nlrsbook_shelf_card col-6 col-sm-4 col-md-2\" data-id=\"" . $book['id'] . "\">
                    <img src=\"" . $value['thumbnailUrl'] . "\" class=\"nlrsbook_shelf_card__img\">
                    <a href=\"" . $value['url'] . "\" target=\"_blank\" class=\"nlrsbook_shelf_card__btn btn btn-primary btn-block btn-sm mt-1\">Читать</a>
                    <div class=\"nlrsbook_shelf_card__title mt-1\">" . $value['title'] . "</div>
                    <div class=\"nlrsbook_shelf_card__author mt-1\">" . $value['title'] . "</div>
                </div>";
}
$content .= pagination($count, $page);

function pagination($count, $page)
{
    $output .= "<div class=\"nlrsbook_shelf_pagination col-12\"><ul class=\"pagination pagination-sm\">";
    $pages = ceil($count / 50);

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

echo json_encode(['page' => $page, 'html' => $content]);