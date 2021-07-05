"use strict";

$(function () {
    $(".change-status").click(function (e) {
        e.preventDefault();
        var macId = $(this).data("id");

        $.ajax({
            method: "POST",
            url: "/mac/switch/",
            dataType: "json",
            data: {id: macId}
        }).done(function (response) {
            if (response.success) {
                location.reload();
            }
        });
    });
});