import Vue from 'vue'
import Router from 'vue-router'
import Root from './pages/dashboard/Root'

function loadView(view) {
    return () =>
        import (`./pages/${view}.vue`);
}

Vue.use(Router);

//DEFINE ROUTE
const router = new Router({
    mode: 'history',
    routes: [{
            path: '/',
            component: Root,
            // meta: { auth: true },
            redirect: '/home'
        },
        {
            path: '/home',
            component: loadView('testing/Index')
        },
        {
            path: '/login',
            component: loadView('dashboard/Login')
        },
        {
            path: '/reset-password',
            name: 'reset_password',
            component: loadView('dashboard/ResetEmailSend')
        },
        {
            path: '/reset-password-code',
            name: 'reset_password_code',
            component: loadView('dashboard/ResetCodeSend')
        },
        {
            path: '/reset-password-confirmation',
            name: 'reset_password_confirmation',
            component: loadView('dashboard/ResetPassword')
        },
    ]
});

export default router;