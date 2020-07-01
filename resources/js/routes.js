import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
Vue.use(VueRouter);

import Home from "./views/Home";
import Login from "./views/Login";
import Register from "./views/Register";
import Ticket from "./views/Ticket";
import User from "./views/dashboard/User";
import Admin from "./layouts/Admin";
import Add from "./views/dashboard/Add";
import AdminHome from "./views/dashboard/Admin";

const router = new VueRouter({
    // mode: 'history',
    // linkActiveClass: 'active',
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/login",
            name: "login",
            component: Login
        },
        {
            path: "/register",
            name: "register",
            component: Register
        },
        {
            path: "/ticket/:id",
            name: "ticket",
            component: Ticket
        },
        {
            path: "/user/:id",
            name: "user",
            component: User,
            meta: { requiresAuth: true },
            beforeEnter(to, from, next) {
                if (
                    store.getters["isUser"] &&
                    parseInt(store.state.user.id) === parseInt(to.params.id)
                ) {
                    next();
                } else {
                    next({
                        name: "login"
                    });
                }
            }
        },
        {
            path: "/admin",
            name: "admin",
            component: Admin,
            meta: { requiresAuth: true },
            children: [
                {
                    path: "add",
                    component: Add
                },
                {
                    path: "/",
                    component: AdminHome
                }
            ],
            beforeEnter(to, from, next) {
                if (store.getters["isAdmin"]) {
                    next();
                } else {
                    next({
                        name: "login"
                    });
                }
            }
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.state.loggedIn) {
            next({
                path: "/login",
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
