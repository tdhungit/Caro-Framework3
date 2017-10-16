function list_relate(rel_model, current_model, current_id, subpanel_name) {
    var url = base_url + '/users/popup/' + rel_model + '/' + current_model + '/' + current_id + '/' + subpanel_name;
    generate_ajax_get_modal(url);
}

function action_relate(rel_model, rel_id, subpanel_name, current_model, current_id, func) {
    $.post(base_url + '/supports/save_relate', {
        rel_model: rel_model,
        rel_id: rel_id,
        subpanel_name: subpanel_name,
        current_model: current_model,
        current_id: current_id,
        func: func
    }, function() {
        $('#reload-subpanel-' + subpanel_name).click();
        $('#systemModal').modal('hide');
    });
}

function save_relate(rel_model, rel_id, subpanel_name, current_model, current_id) {
    action_relate(rel_model, rel_id, subpanel_name, current_model, current_id, 'ins');
}

function remove_relate(rel_model, rel_id, subpanel_name, current_model, current_id) {
    action_relate(rel_model, rel_id, subpanel_name, current_model, current_id, 'del');
}

function create_record_and_save_relate(controller, rel_model, subpanel_name, type, current_model, current_id) {
    quick_create(controller, rel_model,
        'save_and_select_subpanel|' + rel_model + ',' +
        subpanel_name + ',' + type + ',' + current_model + ',' + current_id);
}

function save_and_select_subpanel(data, parameters) {
    var params = parameters.split(',');
    var rel_model = params[0];
    var subpanel_name = params[1];
    var type = params[2];
    var current_model = params[3];
    var current_id = params[4];

    if (data.status == 1) {
        if (type == 'one-many') {
            openAlert(data.message);
            $('#reload-subpanel-' + subpanel_name).click();
            $('#systemModal').modal('hide');
        } else {
            openAlert(data.message);
            save_relate(rel_model, data.data.id, subpanel_name, current_model, current_id);
        }
    } else {
        openAlert(data.message);
    }
}