jQuery(function() {
    // Check All Record List View
    $('input[name=choose_record_all]').on('ifChecked', function () {
        $('input[type=checkbox].check-choose-record').iCheck('check');
    });
    // UnCheck All Record List View
    $('input[name=choose_record_all]').on('ifUnchecked', function () {
        $('input[type=checkbox].check-choose-record').iCheck('uncheck');
    });
    // init data
    init();
    // notifications
    $.get(base_url + '/users/notifications', function (data) {
        $('#notifications-menu').html(data);
    });
});

function init() {
    // DateTime picker
    $('input.datepicker, input.datetimepicker').datetimepicker({

    });
    // iCheck
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
    // Select2
    $('select.select2').select2({
        allowClear: true,
        placeholder: function(){
            $(this).data('placeholder');
        }
    });
    // ajax load
    $('a.ajax-load').click(function (event) {
        if ($(this).attr('element-body')) {
            event.preventDefault();
            generate_ajax_get($(this).attr('href'), $(this).attr('element-body'));
        }
    });
    // ajax upload
    $('input.input-upload-file').change(function () {
        $this = $(this);
        var type = $this.attr('up-type');
        var element_value = $this.attr('e-value');
        var element_container = $this.attr('e-container');
        var formData = new FormData();
        formData.append('file', $(this)[0].files[0]);
        formData.append('location', $(this).attr('location'));
        $.ajax({
            type: "POST",
            url: base_url + "/supports/upload",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                $(element_value).val(res[0].uri + res[0].name);
                if (type == 'image') {
                    $(element_container).html('<div class="uploaded-image-container">' +
                        '<img src="' + res[0].path + '" class="img-thumbnail" style="height: 200px;">' +
                        '<a href="javascript:void(0)" class="uploaded-file">x</a></div>');
                } else {
                    $(element_container).html('<div>' + res[0].path +
                        '<a href="javascript:void(0)" class="uploaded-file">x</a></div>');
                }
                $this.val('');
                // remove file upload
                $('.uploaded-file').click(function () {
                    $(element_value).val('');
                    $(this).parent().remove();
                });
            }
        });
    });
    // ajax multi upload
    $('input.input-upload-files').change(function () {
        $this = $(this);
        var type = $this.attr('up-type');
        var element_value = $this.attr('e-value');
        var element_container = $this.attr('e-container');
        var formData = new FormData();
        var files = $(this)[0].files;
        for (var i in files) {
            formData.append('file[]', files[i]);
        }
        formData.append('location', $(this).attr('location'));
        $.ajax({
            type: "POST",
            url: base_url + "/supports/upload",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                var value = '';
                for (var i in res) {
                    var r = res[i];
                    value += r.uri + r.name + ',';
                    if (type == 'image') {
                        $(element_container).append('<div class="uploaded-image-container">' +
                            '<img src="' + r.path + '" class="img-thumbnail" style="height: 200px;">' +
                            '<a href="javascript:void(0)" class="uploaded-files" path="' + r.uri + r.name + '">x</a></div>');
                    } else {
                        $(element_container).append('<div>' + r.path +
                            '<a href="javascript:void(0)" class="uploaded-files" path="' + r.uri + r.name + '">x</a></div>');
                    }
                }
                value = trim(value, ',');
                var old_value = $(element_value).val();
                if (old_value) {
                    value = old_value + ',' + value;
                }
                $(element_value).val(value);
                $this.val('');
                // remove file upload
                $('.uploaded-files').click(function () {
                    value = value.replace($(this).attr('path'), '');
                    value = trim(value, ',');
                    $(element_value).val(value);
                    $(this).parent().remove();
                });
            }
        });
    });
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

function bodauTiengViet(str) {
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    return str.replace(' ', '-');
}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}

function trim(str, characters) {
    var nativeTrim = String.prototype.trim;
    if (!characters && nativeTrim) return nativeTrim.call(str);
    return str.replace(new RegExp('^' + characters + '+|' + characters + '+$', 'g'), '');
}

function getBodyClass() {
    if ($('body').hasClass('sidebar-collapse')) {
        setCookie('is_collapse', 0, 1);
    } else {
        setCookie('is_collapse', 1, 1);
    }
}

function generateOptionsSelect(json, element_select, target_value) {
    $.each(json, function(i, value) {
        if (i == target_value) {
            $(element_select).append($('<option>').text(value).attr('value', i)).attr('selected');
        } else {
            $(element_select).append($('<option>').text(value).attr('value', i));
        }
    });
}

function startLoading(element) {
    $(element).html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
}

function reload_page() {
    window.location.reload();
}

function openAlert(text) {
    swal(text);
}

function openModal(data) {
    $('#systemModalContent').html(data);
    $('#systemModal').modal({backdrop: 'static'});
}

function openModelWithHeader(title, data) {
    var html = '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<h4 class="modal-title">' + title + '</h4>' +
        '</div>' +
        '<div class="modal-body">' + data +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
        '</div>';
    $('#systemModalContent').html(html);
    $('#systemModal').modal({backdrop: 'static'});
}

function generate_ajax_get_modal(url, title) {
    $.get(url, function (data) {
        if (title) {
            openModelWithHeader(title, data);
        } else {
            openModal(data);
        }
    });
}

function generate_ajax_post_modal(url, post) {
    $.post(url, post, function (data) {
        openModal(data);
    });
}

function generate_ajax_get(url, element_body, is_init) {
    startLoading(element_body);
    var ajax_url = url;
    $.get(ajax_url, function (data) {
        $(element_body).html(data);
        if (is_init == true) {
            init();
        }
    });
}

function generate_ajax_post(url, post, element_body) {
    var ajax_url = base_url + url;
    if (url.indexOf('http://')) {
        ajax_url = url;
    }
    $.post(ajax_url, post, function (data) {
        $(element_body).html(data);
    })
}

function pagination_popup(url) {
    $.get(url, function(data) {
        $('#systemModalContent').html(data);
    });
}

function popup_search(form) {
    var url = form.attr('action') + '?' + form.serialize();
    $.get(url, function(data) {
        $('#systemModalContent').html(data);
    });
}

function quick_create(controller, model_name, callback) {
    if (!model_name) model_name = '';
    if (!callback) callback = '';
    $.get(base_url + '/' + controller + '/quick_create?model_name=' + model_name + '&submit_callback=' + callback, function (data) {
        openModal(data);
        init();
    });
}

function quick_edit(controller) {
    var return_url = $('input[name=return_url]').val();
    var hasChecked = false;
    var ids = [];
    $('input[type=checkbox].check-choose-record:checked').each(function (index, value) {
        hasChecked = true;
        ids.push($(this).val());
    });

    if (hasChecked) {
        $.post(base_url + '/' + controller + '/quick_edit', {choose_records: ids, return_url: return_url}, function (data) {
            openModal(data);
        });
    }
}

function delete_all(controller) {
    var return_url = $('input[name=return_url]').val();
    var alert_message = $('input[name=delete_all_message]').val();
    var hasChecked = false;
    var ids = [];
    $('input[type=checkbox].check-choose-record:checked').each(function (index, value) {
        hasChecked = true;
        ids.push($(this).val());
    });

    if (hasChecked) {
        swal({
                title: 'Warning',
                text: alert_message,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            },
            function() {
                $.post(base_url + '/' + controller + '/delete', {choose_records: ids, return_url: return_url}, function (data) {
                    window.location.reload();
                });
            });
    }
}

function ajax_submit(form, callback) {
    var action = form.attr('action');
    var method = form.attr('method');
    if (!method) {
        method = 'get';
    }

    if (method == 'get') {
        var url = action + '?' + form.serialize();
        $.get(url, function(data) {
            if (callback) {
                var callback_arr = callback.split('|');
                var callback_function = callback_arr[0];
                var callback_data = callback_arr[1];
                window[callback_function](data, callback_data);
            } else {
                $('#systemModalContent .modal-body').html(data);
                $('#systemModalContent .modal-footer button[type=submit]').hide();
            }
        });
    } else {
        $.post(action, form.serialize(), function (data) {
            if (callback) {
                var callback_arr = callback.split('|');
                var callback_function = callback_arr[0];
                var callback_data = callback_arr[1];
                window[callback_function](data, callback_data);
            } else {
                $('#systemModalContent .modal-body').html(data);
                $('#systemModalContent .modal-footer button[type=submit]').hide();
            }
        });
    }
}

function toDisplayDate(date, convert) {
    if (convert == true) {
        return moment(date, 'YYYY-MM-DD').tz(preference.timezone).format(preference.date_format);
    } else {
        return moment(date, 'YYYY-MM-DD').format(preference.date_format);
    }
}

function toDbDate(date, convert) {
    if (convert == true) {
        return moment(date, preference.date_format).tz('UTC').format('YYYY-MM-DD');
    } else {
        return moment(date, preference.date_format).format('YYYY-MM-DD');
    }
}

function toDisplayDatetime(datetime, convert) {
    if (convert == true) {
        return moment(datetime, 'YYYY-MM-DD HH:mm:ss').tz(preference.timezone).format(preference.date_time_format);
    } else {
        return moment(datetime, 'YYYY-MM-DD HH:mm:ss').format(preference.date_time_format);
    }
}

function toDbDatetime(datetime, convert) {
    if (convert == true) {
        return moment(datetime, preference.date_time_format).tz('UTC').format('YYYY-MM-DD HH:mm:ss');
    } else {
        return moment(datetime, preference.date_time_format).format('YYYY-MM-DD HH:mm:ss');
    }
}

function sortingNow(current_url, sort_field, sort_type, is_return) {
    sort_type = sort_type.replace('_', '');
    var new_sort_type = 'desc';
    if (sort_type == 'desc') {
        new_sort_type = 'asc';
    }
    current_url = removeParam('sort', current_url);
    current_url = current_url + '&sort=' + sort_field + '__' + new_sort_type;
    if (is_return == true) {
        return current_url;
    } else {
        location.href = current_url;
        return true;
    }
}

function initCommentEditor(relate_type, relate_id, element_body) {
    startLoading(element_body);
    var ajax_url = base_url + '/comments/show/' + relate_type + '/' + relate_id;
    $.get(ajax_url, function (data) {
        $(element_body).html(data);
        window['emojioneArea_' + relate_type + relate_id] = $('#input-comment-' + relate_type + relate_id).emojioneArea({
            saveEmojisAs: true
        });
        init();
    });
}
