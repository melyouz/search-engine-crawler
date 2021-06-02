import Vue from 'vue'
import http from './http'
import App from './App.vue'

import vuetify from './plugins/vuetify'

// Vue plugins
Vue.prototype.$http = http;

// App
new Vue({
    vuetify,
    el: '#vue-app',
    template: '<app/>',
    components: {App}
});