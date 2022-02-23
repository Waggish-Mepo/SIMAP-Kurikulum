import Vue from 'vue'
import Router from 'vue-router'
import Root from './pages/dashboard/Root'

function loadView(view) {
    return () => import(`./pages/${view}.vue`);
}

Vue.use(Router);

//DEFINE ROUTE
const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Root,
            redirect: '/dashboard',
        },
        {
            path: '*',
            component: loadView('errors/404'),
        },
        {
            path: '/login',
            name: 'login',
            component: loadView('dashboard/Login'),
        },
        {
            path: '/dashboard',
            component: Root,
            meta: { auth: true },
            children: [{ 
                path: '/',
                name: 'dashboard',
                component: loadView('dashboard/Home')
            },
            {
                path: '/:page/mata-pelajaran',
                name: 'mata_pelajaran',
                component: loadView('dashboard/Mapel')
            },
            {
                path: '/:page/periode-rapor',
                name: 'periode_rapor',
                component: loadView('dashboard/ReportPeriod')
            },
            {
                path: '/:page/courses',
                name: 'courses',
                component: loadView('dashboard/Course')
            },
            {
                path: '/:page/gradebook',
                name: 'gradebook',
                component: loadView('dashboard/GradeBook')
            },
            {
                path: '/:page/gradebook/nextgradebook',
                name: 'nextgradebook',
                component: loadView('dashboard/GradeBooks/NextGradeBook')
            },
            ]
        },
    ],
});

router.beforeEach((to, from, next) => {
    // const loggedIn = localStorage.getItem('user');
    const token = localStorage.getItem("token_kurikulum");

    if (to.matched.some((record) => record.meta.auth) && !token) {
        next("/login");
        return;
    }
    next();
});

export default router;
