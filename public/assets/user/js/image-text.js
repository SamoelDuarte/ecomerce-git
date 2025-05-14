"use strict";

$(document).ready(function () {
    $('body').on('click', '.remove-img', function () {
        let url = $(this).data('url');
        let name = $(this).data('name');

        $.get(url, { name: name }, function (response) {
            if (response.status == 'success') {
                location.reload();
            }
        }).fail(function (error) {
            console.error("Error removing image:", error);
        });
    });
});
