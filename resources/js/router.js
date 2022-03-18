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
                path: '/:page/teachers',
                name: 'teachers',
                component: loadView('dashboard/Teacher')
            },
            {
                path: '/:page/periode-rapor',
                name: 'periode_rapor',
                component: loadView('dashboard/ReportPeriod')
            },
            {
                path: '/:page/courses',
                name: 'courses',
                component: loadView('dashboard/courses/Course')
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
                component: loadView('dashboard/batches/Batch')
            },
            {
                path: '/:page/batches/:batch/student-groups',
                name: 'student_groups',
                component: loadView('dashboard/batches/StudentGroup')
            },
            {
                path: '/:page/batches/:batch/student-groups/:group/students',
                name: 'students',
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
                component: loadView('dashboard/reportbooks/Period')
            },
            {
                path: '/:page/reportbooks/periods/:period/students',
                name: 'reportbooks.periods.students',
                component: loadView('dashboard/reportbooks/Students')
            },
            {
                path: '/:page/reportbooks/periods/:period/students/:student',
                name: 'reportbooks.periods.students.report',
                component: loadView('dashboard/reportbooks/StudentReport')
            },
            {
                path: '/:page/reportbooks/periods/:period/groups/:group',
                name: 'reportbooks.periods.students.absence',
                component: loadView('dashboard/reportbooks/StudentAbsence')
            },
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
        }
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
