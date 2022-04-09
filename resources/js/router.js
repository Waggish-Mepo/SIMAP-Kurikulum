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
            meta: { auth: true },
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
                meta: { isAdmin: true },
                component: loadView('dashboard/Mapel')
            },
            {
                path: '/:page/teachers',
                name: 'teachers',
                meta: { isAdmin: true },
                component: loadView('dashboard/Teacher')
            },
            {
                path: '/:page/periode-rapor',
                name: 'periode_rapor',
                meta: { isAdmin: true },
                component: loadView('dashboard/ReportPeriod')
            },
            {
                path: '/:page/courses',
                name: 'courses',
                meta: { isAdmin: true },
                component: loadView('dashboard/courses/Course')
            },
            {
                path: '/:page/teacher-role/courses',
                name: 'courses.teacher-role',
                meta: { isTeacher: true },
                component: loadView('dashboard/teacherRole/Course')
            },
            {
                path: '/:page/courses/:course',
                name: 'courses.students',
                component: loadView('dashboard/courses/Students')
            },
            {
                path: '/:page/courses/:course/add',
                name: 'courses.students.add',
                component: loadView('dashboard/courses/Add')
            },
            {
                path: '/:page/batches',
                name: 'batches',
                meta: { isAdmin: true },
                component: loadView('dashboard/batches/Batch')
            },
            {
                path: '/:page/batches/:batch/student-groups',
                name: 'student_groups',
                meta: { isAdmin: true },
                component: loadView('dashboard/batches/StudentGroup')
            },
            {
                path: '/:page/batches/:batch/student-groups/:group/students',
                name: 'students',
                meta: { isAdmin: true },
                component: loadView('dashboard/batches/StudentData')
            },
            {
                path: '/:page/gradebooks/periods',
                name: 'gradebooks.period',
                component: loadView('dashboard/gradeBook/Period')
            },
            {
                path: '/:page/gradebooks/periods/:period',
                name: 'gradebooks.course',
                component: loadView('dashboard/gradeBook/PeriodCourse')
            },
            { 
                path: '/:page/gradebooks/periods/:period/course/:course/gradebook/:gb',
                name: 'gradebooks.course.detail',
                component: loadView('dashboard/gradeBook/Detail')
            },
            {
                path: '/:page/reportbooks/periods',
                name: 'reportbooks.periods',
                meta: { isAdmin: true },
                component: loadView('dashboard/reportbooks/Period')
            },
            {
                path: '/:page/reportbooks/periods/:period/students',
                name: 'reportbooks.periods.students',
                meta: { isAdmin: true },
                component: loadView('dashboard/reportbooks/Students')
            },
            {
                path: '/:page/reportbooks/periods/:period/students/:student',
                name: 'reportbooks.periods.students.report',
                meta: { isAdmin: true },
                component: loadView('dashboard/reportbooks/StudentReport')
            },
            {
                path: '/:page/reportbooks/periods/:period/groups/:group',
                name: 'reportbooks.periods.students.absence',
                meta: { isAdmin: true },
                component: loadView('dashboard/reportbooks/StudentAbsence')
            },
            {
                path: '/:page/reportbooks/periods/:period/attitude-components',
                name: 'reportbooks.periods.attitude.components',
                meta: { isAdmin: true },
                component: loadView('dashboard/reportbooks/attitude/Component')
            }
            ]
        },
        {
            path: '/dashboard/gradebooks',
            component: loadView('dashboard/BaseGradeBook'),
            meta: { auth: true },
            children: [
                { 
                    path: '/periods/:period/course/:course/gradebook/:gb/student-group/:sg',
                    name: 'gradebooks.course.detail.group',
                    component: loadView('dashboard/gradeBook/DetailGroup')
                },
            ]
        },
    ],
});

// router.beforeEach((to, from, next) => {
//     const token = localStorage.getItem("token_kurikulum");

//     if (to.matched.some((record) => record.meta.auth) && !token) {
//         next("/login");
//         return;
//     }
//     next();
// });
router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user_data');
    const token = localStorage.getItem('token_kurikulum');

    if (to.matched.some(record => record.meta.auth)) {
        if (!loggedIn && !token) {
            next('/login')
        } else {
            let user = JSON.parse(localStorage.getItem('user_data'));
            let role = user.role;
            if (to.matched.some(record => record.meta.isAdmin)) {
                if (role.includes('ADMIN')) next()
                else if (role === 'TEACHER') {
                    next({
                        name: 'dashboard'
                    })
                } else next('/login')
            } else if (to.matched.some(record => record.meta.isTeacher)) {
                if (role.includes('TEACHER')) next()
                else if (role === 'ADMIN') {
                    next({
                        name: 'dashboard'
                    })
                } else next('/login')
            } else {
                next()
            }
        }
    }
    next();
})

export default router;
