/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import Global from './../../../../../resources/assets/js/mixins/global';

import Form from './../../../../../resources/assets/js/plugins/form';

import DashboardPlugin from './../../../../../resources/assets/js/plugins/dashboard-plugin';
import BulkAction from './../../../../../resources/assets/js/plugins/bulk-action';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('customer'),
            bulk_action: new BulkAction('customers'),
            can_login : false
        }
    },

    mounted() {
        this.form.create_user = false;
    },

    methods:{
        onCanLogin(event) {
            if (event.target.checked) {
                if (this.form.email) {
                    axios.get(url + '/auth/users/autocomplete', {
                        params: {
                            column: 'email',
                            value : this.form.email
                        }
                    })
                        .then(response => {
                            if (response.data.errors) {
                                if (response.data.data) {
                                    this.form.errors.set('email', {
                                        0: response.data.data
                                    });

                                    return false;
                                }

                                this.can_login = true;
                                this.form.create_user = true;
                                return true;
                            }

                            if (response.data.success) {
                                this.form.errors.set('email', {
                                    0: can_login_errors.email
                                });

                                this.can_login = false;
                                this.form.create_user = false;
                                return false;
                            }
                        })
                        .catch(error => {
                        });
                } else {
                    this.form.errors.set('email', {
                        0: can_login_errors.valid
                    });

                    this.can_login = false;
                    this.form.create_user = false;
                    return false;
                }

                return false;
            } else {
                this.form.errors.clear('email');

                this.can_login = false;
                this.form.create_user = false;
                return false;
            }
        },
        updateCompanyInfo(event){
            const company_id = event.target.getAttribute('data-company_id');;
            axios.post('company-data/updateInfo', {
                id: company_id
            })
                .then(response => {
                    this.form.loading = false;

                    if (response.data.redirect) {
                        this.form.loading = true;

                        window.location.href = response.data.redirect;
                    }

                    this.form.response = response.data;
                })
                .catch(error => {
                    this.form.loading = false;
                });
        }
    }
});
