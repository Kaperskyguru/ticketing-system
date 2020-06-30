import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Home from './views/Home'
import Login from './views/Login'
import Register from './views/Register'
import Ticket from './views/Ticket'
import User from './views/dashboard/User'
import Admin from './layouts/Admin'
import Add from './views/dashboard/Add'
import AdminHome from './views/dashboard/Admin'

const router = new VueRouter({
    // mode: 'history',
    // linkActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/ticket/:id',
            name: 'ticket',
            component: Ticket
        },
        {
            path: '/user',
            name: 'user',
            component: User
        },
        {
            path: '/admin',
            name: 'admin',
            component: Admin,
            children:[
            	{
		            path: 'add',
		            name: 'admin-add',
		            component: Add
        		},
        		{
		            path: '/',
		            name: 'admin-home',
		            component: AdminHome
        		},
            ]
        },
    ],
});
export default router;