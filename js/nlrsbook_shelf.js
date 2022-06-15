$(document).ready(function () {
    send_request_nlrsbook();
});

function send_request_nlrsbook(page = 1, remove = null) {
    $.ajax({
        url: M.cfg.wwwroot + "/blocks/nlrsbook_shelf/ajax.php?page=" + page + "&remove=" + remove 
    }).done(function (data) {
        $("#nlrsbook_shelf_list").html(data.html);
        console.log(data.remove)
        $(".nlrsbook-page").click(function () {
            send_request_nlrsbook($(this).data('page'), null);
        });
        $(".nlrsbook-remove").click(function () {
            send_request_nlrsbook(page, $(this).data('remove'));
        });
    });
}