"use strict";

$(function () {
    $("#exportToSubnet").click(function (e) {
        e.preventDefault();

        $(".subnet-form-wrapper").fadeIn(600);
    });

    $(".subnet-form-wrapper form").submit(function (e) {
        e.preventDefault();

        $.ajax({
            method: "post",
            url: "/mac/exportSubnet/",
            data: $(this).serialize(),
        }).done(function (fileName) {
            var link = document.createElement('a');

            link.href = '/docs/' + fileName;
            link.download = fileName;
            link.click();
        });
    });
});
