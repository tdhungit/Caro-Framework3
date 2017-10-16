function dependencyFieldSelect(element_source, element_target, data, target_value) {
    var ctarget = $(element_target).html();
    $(element_source).change(function () {
        var cval = $(this).val();
        if (data[cval]) {
            $(element_target).html('');
            generateOptionsSelect(data[cval], element_target, target_value);
        } else {
            $(element_target).html(ctarget);
        }
    });
}

function dependencyFieldDbSelect(element_source, element_target, target_table, fk, target_value) {
    if (!fk) fk = '';
    $(element_source).change(function () {
        var pval = $(this).val();
        if (pval) {
            $.get(base_url + '/supports/dpfields?type=dbselect&source_value=' + pval +
                '&target_table=' + target_table + '&fk=' + fk + '&target_value=' + target_value, function (data) {
                $(element_target).select2('destroy').empty().select2({data: data});
                $(element_target).change();
            });
        }
    });
    $(element_source).change();
}

function dependencyFieldRelate(element_source, element_target, target_model, fk, target_value) {
    if (!fk) fk = '';
    $(element_source).change(function () {
        var pval = $(this).val();
        if (pval) {
            $.get(base_url + '/supports/dpfields?type=dbselect&source_value=' + pval +
                '&target_model=' + target_model + '&fk=' + fk + '&target_value=' + target_value, function (data) {
                $(element_target).select2('destroy').empty().select2({data: data});
                $(element_target).change();
            });
        }
    });
    $(element_source).change();
}
