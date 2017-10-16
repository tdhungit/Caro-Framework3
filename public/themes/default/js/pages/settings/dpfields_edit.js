componentSelect2('', true, '#select2-template-target_value');
var app = new Vue({
    el: '#dependency-fields-content',
    data: {
        id: 0,
        type: 'select',
        model_name: '',
        select_fields: [],
        source_name: '',
        source_values: [],
        source_value: '',
        target_name: '',
        target_values: [],
        target_value: []
    },
    methods: {
        resetData: function () {
            this.model_name = '';
            this.select_fields = [];
            this.source_name = '';
            this.source_values = [];
            this.source_value = '';
            this.target_name = '';
            this.target_values = [];
            this.target_value = [];
            componentSelect2('', true, '#select2-template-target_value');
        },
        getSelectFields: function (source_name, target_name) {
            this.source_value = '';
            this.target_value = [];
            this.select_fields = [];
            if (this.type && this.model_name) {
                this.$http.get(base_url + '/settings/dpfields_data/' + this.type + '/' + this.model_name + '/select_fields').then(response => {
                    this.select_fields = response.body;
                    if (source_name && target_name) {
                        this.source_name = source_name;
                        this.target_name = target_name;
                    }
                });
            }
        },
        getSourceValues: function (source_value) {
            if (this.type && this.model_name && this.source_name) {
                this.$http.get(base_url + '/settings/dpfields_data/' + this.type + '/' + this.model_name + '/select_values/' + this.source_name).then(response => {
                    this.source_values = response.body;
                    if (source_value) {
                        this.source_value = source_value;
                    }
                });
            }
        },
        getTargetValues: function (target_value) {
            if (this.type && this.model_name && this.target_name) {
                this.$http.get(base_url + '/settings/dpfields_data/' + this.type + '/' + this.model_name + '/select_values/' + this.target_name).then(response => {
                    this.target_values = response.body;
                    if (target_value) {
                        this.target_value = target_value;
                    }
                    componentSelect2('', true, '#select2-template-target_value');
                });
            }
        },
        created: function () {
            this.type = $('#dpfield_type').val();
            if (!this.type) {
                this.type = 'select';
            }
            this.id = $('#dpfield_id').val();
            if (this.id) {
                this.$http.get(base_url + '/settings/dpfields_edit/' + this.id + '?is_ajax=1').then(response => {
                    var data = response.body;
                    this.model_name = data.model_name;
                    this.source_name = data.source_name;
                    this.source_value = data.source_value;
                    this.target_name = data.target_name;
                    this.getSelectFields(data.source_name, data.target_name);
                    this.getSourceValues(data.source_value);
                    this.getTargetValues(data.target_value);
                });
            }
        }
    }
});

app.created();