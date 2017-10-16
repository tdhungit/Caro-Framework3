componentSelect2('/activities/get_invite', false, '#select2-template');
var app = new Vue({
    el: '#invite-member-content',
    data: {
        invite_member: '',
        invite_members: []
    },
    methods: {
        getInvite: function () {
            this.invite_members.push({email: this.invite_member});
        }
    }
});