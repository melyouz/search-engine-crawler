import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify);

const opts = {
    theme: {
        themes: {
            light: {
                primary: '#753474', // Nemon purple
                secondary: '#4F46E5', // indigo-600
                accent: '#312E81', // indigo-900
                error: '#f44336',
                warning: '#ff5722',
                info: '#03a9f4',
                success: '#4caf50',
            }
        }
    }
};

export default new Vuetify(opts)