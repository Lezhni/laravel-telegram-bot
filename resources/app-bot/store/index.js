import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth'
import common from './common'
import users from './users'
import menu from './menu'
import reorder from './reorder'
import feedback from './feedback'

Vue.use(Vuex);

export default new Vuex.Store({
    modules : {
        auth, common, users, menu, reorder, feedback
    }
})
