import Vue from "vue";
import VueRouter from "vue-router";
import axios from 'axios'
import store from '../store';
import middlewarePipeline from "./middlewarePipeline";

Vue.use(VueRouter);

import Users from '../views/Users.vue'
import Menu from '../views/Menu.vue'
import Reorder from '../views/Reorder.vue'
import Feedback from '../views/Feedback.vue'
import Login from '../views/Login.vue'

import middlewareAuth from './middleware/middlewareAuth'
import middlewareNotAuth from './middleware/middlewareNotAuth'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'main',
            redirect: '/login',
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/users',
            name: 'users',
            component: Users,
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/menu',
            name: 'menu',
            component: Menu,
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/menu/reorder',
            name: 'reorder',
            component: Reorder,
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/feedback',
            name: 'feedback',
            component: Feedback,
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                middleware:  [
                    middlewareNotAuth
                ]
            },
        }
    ]
})

router.beforeEach((to, from, next) => {
    const startMiddleware = () => {
        if (!to.meta['middleware']) {
            return next()
        }
        const middleware = to.meta['middleware']

        const context = {
            to,
            from,
            next,
            store
        }
        return middleware[0]({
            ...context,
            nextMiddleware: middlewarePipeline(context, middleware, 1)
        })
    }

    startMiddleware()
})

export default router
