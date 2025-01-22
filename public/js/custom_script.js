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

    $("#frmAdd").submit(function(event) {
        event.preventDefault();

        var form = $("#frmAdd");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');
        form.find(".form-group").removeClass('is-invalid').find('.help-block').remove();

        if (form_valid("#frmAdd")) {
            var curObj = $(this);

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
                        if (data.errors) {
                            // Clear any previous errors
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').remove();

                            $.each(data.errors, function(field, messages) {
                                var fieldName = field.replace('.', '\\.') // Escape dots for jQuery selectors
                                var errorMessage = messages.join('<br>'); // Join multiple messages if any

                                // Add the 'is-invalid' class to the form group
                                $('#' + fieldName).closest('.form-group').addClass('is-invalid');

                                // Display the error message
                                $('#' + fieldName).after('<div class="invalid-feedback">' + errorMessage + '</div>');
                            });

                            // Optionally scroll to the first invalid field
                            $('html, body').animate({
                                scrollTop: $('.is-invalid .form-control').first().offset().top - 200
                            }, 1000);
                        }
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
                },
                error: function( error) {
                    console.error("AJAX Error Response:", error); // Log the error for debugging
                    $('button[type="submit"]').prop('disabled', false);
                    // Clear previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (error.responseJSON && error.responseJSON.errors) {
                        const errors = error.responseJSON.errors;

                        $.each(errors, function (key, messages) {
                            // Example: questions.1.correct -> [questions, 1, correct]
                            const parts = key.split('.');
                            let field;

                            // Handle dynamic fields
                            if (parts.length === 3 && parts[0] === 'questions') {
                                const questionId = parts[1]; // Extract question index
                                const fieldName = parts[2]; // Extract field type (e.g., correct, options)

                                if (fieldName === 'correct') {
                                    // Radio button for correct answer
                                    field = $(`input[name="questions[${questionId}][${fieldName}]"]`);
                                } else {
                                    // Other input fields
                                    field = $(`input[name="questions[${questionId}][${fieldName}]"]`);
                                }
                            }

                            if (field && field.length) {
                                // Add error class and display message
                                field.addClass('is-invalid');
                                field.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                            } else {
                                // Log if the field is not found
                                console.warn('Field not found in form:', key);
                                $.each(errors, function(key, messages) {
                                    // For each error field, process the array of messages
                                    $.each(messages, function(index, message) {
                                        // Ensure we don't duplicate error elements
                                        let inputElement = $('#' + key);
                                        let errorElementId = key + '_error';

                                        // Check if the error message block already exists
                                        if ($('#' + errorElementId).length === 0) {
                                            // Add error styling and message
                                            inputElement.closest('.form-group').addClass('is-invalid');
                                            inputElement.after('<div id="' + errorElementId + '" class="help-block invalid-feedback">' + message + '</div>');
                                        }

                                        // Show the error message
                                        $('#' + errorElementId).show();
                                    });
                                });
                            }
                        });
                    } else {
                        console.warn('No validation errors found in the response.');
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
                },
                error: function( error) {
                    console.error("AJAX Error Response:", error); // Log the error for debugging
                    $('button[type="submit"]').prop('disabled', false);
                    // Clear previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (error.responseJSON && error.responseJSON.errors) {
                        const errors = error.responseJSON.errors;

                        $.each(errors, function (key, messages) {
                            // Example: questions.1.correct -> [questions, 1, correct]
                            const parts = key.split('.');
                            let field;

                            // Handle dynamic fields
                            if (parts.length === 3 && parts[0] === 'questions') {
                                const questionId = parts[1]; // Extract question index
                                const fieldName = parts[2]; // Extract field type (e.g., correct, options)

                                if (fieldName === 'correct') {
                                    // Radio button for correct answer
                                    field = $(`input[name="questions[${questionId}][${fieldName}]"]`);
                                } else {
                                    // Other input fields
                                    field = $(`input[name="questions[${questionId}][${fieldName}]"]`);
                                }
                            }

                            if (field && field.length) {
                                // Add error class and display message
                                field.addClass('is-invalid');
                                field.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                            } else {
                                // Log if the field is not found
                                console.warn('Field not found in form:', key);
                                $.each(errors, function(key, messages) {
                                    // For each error field, process the array of messages
                                    $.each(messages, function(index, message) {
                                        // Ensure we don't duplicate error elements
                                        let inputElement = $('#' + key);
                                        let errorElementId = key + '_error';

                                        // Check if the error message block already exists
                                        if ($('#' + errorElementId).length === 0) {
                                            // Add error styling and message
                                            inputElement.closest('.form-group').addClass('is-invalid');
                                            inputElement.after('<div id="' + errorElementId + '" class="help-block invalid-feedback">' + message + '</div>');
                                        }

                                        // Show the error message
                                        $('#' + errorElementId).show();
                                    });
                                });
                            }
                        });
                    } else {
                        console.warn('No validation errors found in the response.');
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
