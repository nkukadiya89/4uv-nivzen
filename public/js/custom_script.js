var timeoutID;
var email_flag = true; // for email unique validation

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === undefined) ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function rtrim(str, lastChar) {
    if (str.substring(str.length - 1) == lastChar) {
        str = str.substring(0, str.length - 1);
    }
    return str;
}

function strstr(haystack, needle, bool) {
    var pos = 0;

    haystack += "";
    pos = haystack.indexOf(needle);
    if (pos == -1) {
        return false;
    } else {
        if (bool) {
            return haystack.substr(0, pos);
        } else {
            return haystack.slice(pos);
        }
    }
}

$(document).ready(function() {

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "50000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    if ($('.alert').length) {
        setTimeout(function() {
            $(".alert").fadeOut(3000);
        }, 5000);
    }

    $(document).ajaxError(function(event, request, settings) {
        if (request.responseText === 'Unauthorized.') {
            window.location = SITE_URL;
        }
    });

    var save_and_continue_flag = false;
    $("#frmAddNewSubmit").click(function() {
        save_and_continue_flag = true
    });

    $("#frmAdd").submit(function() {
       
        var form = $("#frmAdd");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#frmAdd")) {
            var curObj = $(this);``

            if (save_and_continue_flag) {
                curObj.find('#frmAddNewSubmit').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
            } else {
                curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
                curObj.find('#frmAddNewSubmit').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
            }

            $('textarea.ckeditor').each(function() {
                var textarea = $(this);
                textarea.val(editor.getData());
            })


            //   var send_data = $("#frmAdd").serialize();

            var send_data = new FormData($("#frmAdd")[0])

            $.ajax({
                url: $("#frmAdd").attr("action"),
                method: 'post',
                data: send_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data){

                    if (save_and_continue_flag) {
                        curObj.find('#frmAddNewSubmit').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    } else {
                        curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    }

                    if (data.success == true) {

                        $('#i_num_available_error').hide();
                        if (save_and_continue_flag) {
                            save_and_continue_flag = false;
                            window.location.href = window.location.href;
                        } else {
                            save_and_continue_flag = false;
                            window.location.href = (window.location.href).replace('/add', '');
                        }
                        // window.location.href = (window.location.href).replace('/add', '');

                    } else {
                        console.log(data);
                        $(data).each(function(i, val) {
                            $.each(val, function(key, v) {

                                console.log('sdfsfsd');
                                $('#' + key).closest('.form-group').addClass('is-invalid');
                                $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + v + '.</div>');
                                $('#' + key + '_error').show();
                            });
                        });

                        if ($('.is-invalid .form-control').length > 0) {
                            $('html, body').animate({
                                scrollTop: $('.is-invalid .form-control').first().offset().top - 200
                            }, 1000);

                            $('.is-invalid .form-control').first().focus()
                        }
                    }
                }
            });
        }

        if ($('.discount_value').attr('style') != undefined) {
            setTimeout(function() {
                $('.required-radio-discount').find('.help-block').remove();
            }, 100);
        }

        return false;
    });

    $("#frmEdit").submit(function() {

        var form = $("#frmEdit");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#frmEdit")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            $('textarea.ckeditor').each(function() {
                var textarea = $(this);
                textarea.val(editor.getData());
            });



            var send_data = new FormData($("#frmEdit")[0])

           // var send_data = form.serialize();

            $.ajax({
                url: $("#frmEdit").attr("action"),
                method: 'post',
                data: send_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data){

                    curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                    if (data.success == true)  {

                        $('#i_num_available_error').hide();
                        if ($("#redirect_url").length == 1) {
                            window.location.href = $("#redirect_url").val();
                        } else {
                            window.location = strstr($("#frmEdit").attr("action"), '/edit/', true);
                        }

                    }  else {
                    //    data = $.parseJSON(data);
                        data = data;

                        $(data).each(function(i, val) {
                            $.each(val, function(key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');
                                $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + v + '.</div>');
                                $('#' + key + '_error').show();
                            });
                        });

                        if ($('.is-invalid .form-control').length > 0) {
                            $('html, body').animate({
                                scrollTop: $('.is-invalid .form-control').first().offset().top - 200
                            }, 1000);

                            $('.is-invalid .form-control').first().focus()
                        }
                    }
                }
            });
        }

        return false;
    });

    $("#frmEditFleetSpecifications").submit(function() {
        var form = $("#frmEditFleetSpecifications");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#frmEditFleetSpecifications")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            $('textarea.ckeditor').each(function() {
                var textarea = $(this);
                textarea.val(editor.getData());
            });

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {
                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {

                    toastr.success('Fleet Specification Information has been saved successfully.');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 600);

                } else {
                    data = $.parseJSON(data);
                    $(data).each(function(i, val) {
                        $.each(val, function(key, v) {
                            $('#' + key).closest('.form-group').addClass('is-invalid');
                            $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + v + '.</div>');
                            $('#' + key + '_error').show();
                        });
                    });

                    if ($('.is-invalid .form-control').length > 0) {
                        $('html, body').animate({
                            scrollTop: $('.is-invalid .form-control').first().offset().top - 200
                        }, 1000);

                        $('.is-invalid .form-control').first().focus()
                    }
                }
            });
        }

        return false;
    });

    $("#update_profile").submit(function() {
        var form = $("#update_profile");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#update_profile")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {

                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {
                    window.location.reload();
                } else {
                    $(data).each(function(i, val) {
                        $.each(val, function(key, v) {
                            $('#' + key).closest('.form-group').addClass('is-invalid');
                            $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + v + '.</div>');
                            $('#' + key + '_error').show();
                        });
                    });

                    if ($('.is-invalid .form-control').length > 0) {
                        $('html, body').animate({
                            scrollTop: $('.is-invalid .form-control').first().offset().top - 200
                        }, 1000);

                        $('.is-invalid .form-control').first().focus()
                    }
                }
            });
        }

        return false;
    });

});
