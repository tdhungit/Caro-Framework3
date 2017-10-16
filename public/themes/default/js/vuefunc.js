function componentSelect2(ajax_uri, options, element_id_template) {
    if (ajax_uri) { // <select2 v-model="invite_member"></select2>
        Vue.component('select2', {
            props: ['value'],
            template: element_id_template, // select 2 html
            mounted: function () {
                var vm = this;
                $(this.$el)
                // init select2
                    .select2({
                        ajax: {
                            url: base_url + ajax_uri,
                            data: function (params) {
                                return {
                                    q: params.term,
                                    page: params.page
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data.items
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1
                    })
                    .val(this.value)
                    .trigger('change')
                    // emit event on change.
                    .on('change', function () {
                        vm.$emit('input', this.value);
                    })
            },
            watch: {
                value: function (value) {
                    // update value
                    $(this.$el).val(value).trigger('change');
                    this.$emit('change');
                },
                options: function () {
                    // update options
                    $(this.$el).select2({
                        ajax: {
                            url: base_url + ajax_uri,
                            data: function (params) {
                                return {
                                    q: params.term,
                                    page: params.page
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data.items
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1
                    })
                }
            },
            destroyed: function () {
                $(this.$el).off().select2('destroy')
            }
        });
    } else if (options) { // <select2 :options="options" v-model="invite_member"></select2>
        Vue.component('select2', {
            props: ['options', 'value'],
            template: element_id_template, // select 2 html
            mounted: function () {
                var vm = this;
                $(this.$el)
                // init select2
                    .select2({ data: this.options })
                    .val(this.value)
                    .trigger('change')
                    // emit event on change.
                    .on('change', function () {
                        vm.$emit('change');
                        vm.$emit('input', this.value);
                    })
            },
            watch: {
                value: function (value) {
                    // update value
                    $(this.$el).val(value).trigger('change');
                    this.$emit('change');
                },
                options: function (options) {
                    // update options
                    $(this.$el).select2({data: options})
                }
            },
            destroyed: function () {
                $(this.$el).off().select2('destroy')
            }
        });
    }
}