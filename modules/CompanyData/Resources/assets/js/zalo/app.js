import Form from "../../../../../resources/assets/js/plugins/form";

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';
import Global from "../../../../../resources/assets/js/mixins/global";
import DashboardPlugin from "../../../../../resources/assets/js/plugins/dashboard-plugin";

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('adv-zalo')
        }
    },
    methods: {
        onSendMessage(notify) {
            let response = this.form.submit();
            console.log(response);
            notify({
                message: 'sdfsdfsdfsdfsdfds',
                timeout: 5000,
                icon: 'fas fa-bell'
            });
            $('#chatModal').modal('hide');
        },
        onSetUserId: function(event){
            this.form.user_id = event.currentTarget.getAttribute('user_id');
        }
    }
});
