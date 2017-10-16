function loadSubpanel(url, element_body, element_status) {
    if ($(element_status).attr('status') == 'closed') {
        $(element_status).attr('status', 'opened');
        $(element_status).html('<i class="fa fa-chevron-up"></i>');
        $(element_body).show();
        generate_ajax_get(url, element_body);
    } else {
        $(element_status).attr('status', 'closed');
        $(element_status).html('<i class="fa fa-chevron-down"></i>');
        $(element_body).hide();
    }
}

function sortSubpanel(field, sort_type, url, element_body) {
    sort_type = sort_type.replace('_', '');
    var new_sort_type = 'desc';
    if (sort_type == 'desc') {
        new_sort_type = 'asc';
    }
    url = removeParam('sort', url);
    var ajax_url = url + '&sort=' + field + '__' + new_sort_type;
    generate_ajax_get(ajax_url, element_body);
}
