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
    ]
});

export default router;