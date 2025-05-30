(function ($) {
    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("select[name='user_language_id']").on('change', function () {
        let langid = $(this).val();
        $(".request-loader").addClass("show");
        $("#ucategory").removeAttr('disabled');
        $.get(category_url + '?language_id=' + langid, function (data) {
            let options = `<option value="" disabled selected>Select a category</option>`;
            for (let i = 0; i < data.length; i++) {
                options += `<option value="${data[i].id}">${data[i].name}</option>`;
            }
            $("#ucategory").html(options);
            $(".request-loader").removeClass("show");

        });
        $("#pcategory").removeAttr('disabled');
        $.get(mainurl + "/user/portfolio/" + langid + "/getcats", function (data) {
            let options = `<option value="" disabled selected>Select a category</option>`;
            for (let i = 0; i < data.length; i++) {
                options += `<option value="${data[i].id}">${data[i].name}</option>`;
            }
            $("#pcategory").html(options);
            $(".request-loader").removeClass("show");

        });
        if ($(this).parents('form').hasClass('create')) {
            $.get(mainurl + "/user/rtlcheck/" + $(this).val(), function (data) {
                $(".request-loader").removeClass("show");
                $("form.create input, form.create select, form.create textarea").removeClass('ltr');
                if (data == 1) {
                    $("form.create input").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.create select").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.create textarea").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.create .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                    });

                } else {
                    $("form.create input, form.create select, form.create textarea").removeClass('rtl');
                    $("form.create input, form.create select, form.create textarea").addClass('ltr');
                }
            });
        } else if ($(this).parents('form').hasClass('modal-form')) {

            $.get(mainurl + "/user/rtlcheck/" + $(this).val(), function (data) {
                $(".request-loader").removeClass("show");
                $("form.modal-form input, form.create select, form.create textarea").removeClass('ltr');
                if (data == 1) {
                    $("form.modal-form input").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form select").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form textarea").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                    });

                } else {
                    $("form.modal-form input, form.modal-form select, form.modal-form textarea").removeClass('rtl');

                    $("form.modal-form input, form.create select, form.create textarea").addClass('ltr');
                }
            });
        } else {
            // make input fields RTL
            $.get(mainurl + "/user/rtlcheck/" + $(this).val(), function (data) {
                $(".request-loader").removeClass("show");
                $("form input, form.create select, form.create textarea").removeClass('ltr');
                if (data == 1) {
                    $("form input").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form select").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form textarea").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });

                } else {
                    $("form input, form select, form textarea").removeClass('rtl');
                    $("form input, form.create select, form.create textarea").addClass('ltr');
                }
            });
        }

    })


    // make input fields RTL
    $("select[name='language_id']").on('change', function () {
        $(".request-loader").addClass("show");

        // product category load according to language selection
        $("#category").removeAttr('disabled');

        let langid = $(this).val();

        $("#bcategory").removeAttr('disabled');
        $.get(mainurl + "/admin/blog/" + langid + "/getcats", function (data) {
            let options = `<option value="" disabled selected>${select_a_category}</option>`;
            for (let i = 0; i < data.length; i++) {
                options += `<option value="${data[i].id}">${data[i].name}</option>`;
            }
            $("#bcategory").html(options);

        });

        if ($(this).parents('form').hasClass('create')) {
            $.get(mainurl + "/admin/rtlcheck/" + $(this).val(), function (data) {
                $(".request-loader").removeClass("show");
                if (data == 1) {
                    $("form.create input").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.create select").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.create textarea").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });

                    $("form.create .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                    });

                } else {
                    $("form.create input, form.create select, form.create textarea").removeClass('rtl');

                    $("form.create .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
                    });
                }
            });
        } else if ($(this).parents('form').hasClass('modal-form')) {
            $.get(mainurl + "/admin/rtlcheck/" + $(this).val(), function (data) {
                $(".request-loader").removeClass("show");
                if (data == 1) {
                    $("form.modal-form input").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form select").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form textarea").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                    });

                } else {
                    $("form.modal-form input, form.modal-form select, form.modal-form textarea").removeClass('rtl');
                    $("form.modal-form .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
                    });
                }
            });
        } else {
            // make input fields RTL
            $.get(mainurl + "/admin/rtlcheck/" + $(this).val(), function (data) {
                $(".request-loader").removeClass("show");
                if (data == 1) {
                    $("form input").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form select").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form textarea").each(function () {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                    });

                } else {
                    $("form input, form select, form textarea").removeClass('rtl');
                    $("form .summernote").each(function () {
                        $(this).siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
                    });
                }
            });
        }


    });

    // intro section video background preview
    $(document).on('change', '#intro_video_image', function () {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.intro_video_image img').attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    });

    $(document).on('change', '#image', function (event) {
        let file = event.target.files[0];
        let reader = new FileReader();
        reader.onload = function (e) {
            $('.showImage img').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    })

    var today = new Date();
    $("#submissionDate").datepicker({
        autoclose: true,
        endDate: today,
        todayHighlight: true
    });
    $("#startDate").datepicker({
        autoclose: true,
        endDate: today,
        todayHighlight: true
    });

    $("#socialForm").on("submit", function (e) {
        e.preventDefault();
        $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
        document.getElementById('socialForm').submit();
    });

    if ($('.icp').length > 0) {
        $('.icp').on('iconpickerSelected', function (event) {
            $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
        });
    }

    $('.chackFeature').on('click', function () {
        let featureId = $(this).attr('data');
        if ($(this).is(':checked')) {
            $.get(mainurl + '/admin/product/feature/check/' + featureId + ',1', function (response) {
                if (response == "done") {
                    location.reload();
                }
            })
        } else {
            $.get(mainurl + '/admin/product/feature/check/' + featureId + ',0', function (response) {
                if (response == "done") {
                    location.reload();
                }
            })
        }
    });

    $('.chackSpecial').on('click', function () {
        let specialId = $(this).attr('data');
        if ($(this).is(':checked')) {
            $.get(mainurl + '/admin/product/special/check/' + specialId + ',1', function (response) {
                if (response == "done") {
                    location.reload();
                }
            })
        } else {
            $.get(mainurl + '/admin/product/special/check/' + specialId + ',0', function (response) {
                if (response == "done") {
                    location.reload();
                }
            })
        }
    });

    $("#toggle-btn").on('change', function () {
        var value = null;
        if (this.checked) {
            value = this.getAttribute('data-on');
        } else {
            value = this.getAttribute('data-off');
        }
        $.post(user_status, {
            value: value
        },
            function (data) {
                history.go(0);
            });
    });

    $(".template-select").on('change', function () {
        let userId = $(this).data('user_id');
        let val = $(this).val();

        if (val == 1) {
            $("#templateModal" + userId).modal('show');
        }

        $(`#templateModal${userId} input[name='template']`).val(val);
        if (val == 0) {
            $(`#templateForm${userId}`).trigger('submit');
        }
    });

    $("select[name='file_type']").on('change', function () {
        let type = $(this).val();
        if (type == 'link') {
            $("#downloadFile input").attr('disabled', true);
            $("#downloadFile").addClass('d-none');
            $("#downloadLink").removeClass('d-none');
            $("#downloadLink input").removeAttr('disabled');
        } else {
            $("#downloadLink input").attr('disabled', true);
            $("#downloadLink").addClass('d-none');
            $("#downloadFile").removeClass('d-none');
            $("#downloadFile input").removeAttr('disabled');
        }
    });

    WebFont.load({
        google: { "families": ["Lato:300,400,700,900"] },
        custom: { "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: [mainurl + '/assets/admin/css/fonts.min.css'] },
        active: function () {
            sessionStorage.fonts = true;
        }
    });
})(jQuery);
