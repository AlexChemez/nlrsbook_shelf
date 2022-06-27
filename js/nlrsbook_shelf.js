$(document).ready(function () {
    send_request_nlrsbook();
});

function send_request_nlrsbook(page = 1, remove = null) {
    $.ajax({
        url: M.cfg.wwwroot + "/blocks/nlrsbook_shelf/ajax.php?page=" + page + "&remove=" + remove,
    }).done(function (data) {
        if (data.count > 0) {
            $("#nlrsbook_shelf_count").html('('+data.count+')');
        }
        console.log(data.remove);
        $("#nlrsbook_shelf_list").html(data.html);
        $(".nlrsbook-page").unbind("click").click(function () {
            send_request_nlrsbook($(this).data('page'), null);
        });
        $(".nlrsbook-remove").unbind("click").click(function () {
            send_request_nlrsbook(page, $(this).data('remove'));
        });
    });
}