"use strict";

$(function () {
    $(document).on("beforeSubmit", "#generate-form", function () {
        $.ajax({
            method: "post",
            url: "/mac/generate/",
            dataType: "json",
            data: $(this).serialize()
        }).done(function (response) {
            var message = "Mac-адрес сгенерирован: " + response.macAddress.name;
            var responseMessage = $(".response-message");

            responseMessage.find(".alert").html(message);
            responseMessage.fadeIn(600);
        });

        return false;
    });
});