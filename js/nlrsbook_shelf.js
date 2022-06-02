$(document).ready(function () {
    send_request_nlrsbook();
});

function send_request_nlrsbook(page = 1) {
    $.ajax({
        url: M.cfg.wwwroot + "/blocks/nlrsbook_shelf/ajax.php?page=" + page
    }).done(function (data) {
        $("#nlrsbook_shelf_list").html(data.html);
        $(".nlrsbook-page").click(function () {
            send_request_nlrsbook($(this).data('page'));
        });
    });
}