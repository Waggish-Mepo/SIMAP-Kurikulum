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
            redirect: '/home'
        },
        {
            path: '/login',
            component: loadView('dashboard/Login')
        },
        {
            path: '/home',
            name: 'home',
            component: loadView('dashboard/Home'),
            meta: { auth: true },
        },
    ]
});

router.beforeEach((to, from, next) => {
    // const loggedIn = localStorage.getItem('user');
    const token = localStorage.getItem('token_kurikulum');

    if (to.matched.some(record => record.meta.auth) && !token) {
        next('/login')
        return
    }
    next()
});

export default router;