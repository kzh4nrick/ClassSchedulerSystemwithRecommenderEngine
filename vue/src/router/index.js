import {createRouter, createWebHistory} from "vue-router";
import Dashboard from '../views/Dashboard.vue'
import Department from '../views/Department.vue'
import Courses from '../views/Courses.vue'
import Subjects from '../views/Subjects.vue'
import Faculty from '../views/Faculty.vue'
import Classrooms from '../views/Classrooms.vue'
import Curriculum from '../views/Curriculum.vue'
import DefaultLayout from '../components/deflayout.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import store from '../store'
import AuthLayout from "../components/AuthLayout.vue";

const routes = [
    {
        path: "/auth",
        redirect: "/login",
        name: "Auth",
        component: AuthLayout,
        meta: {isGuest: true},
        children: [
          {
            path: "/login",
            name: "Login",
            component: Login,
          },
          {
            path: "/register",
            name: "Register",
            component: Register,
          },
        ],
      },
      // {
      //   path: '/404',
      //   name: 'NotFound',
      //   component: NotFound
      // },
    {
        path: '/',
        redirect: '/class-scheduler',
        component: DefaultLayout,
        meta: {requiresAuth: true},
        children: [
            {path: '/class-scheduler', name: 'Class Scheduler', component: Dashboard},
            {path: '/college-departments', name: 'College Departments', component: Department},
            {path: '/courses', name: 'Courses', component: Courses},
            {path: '/subjects', name: 'Subjects', component: Subjects},
            {path: '/faculty', name: 'Faculty', component: Faculty},
            {path: '/classrooms', name: 'Classrooms', component: Classrooms},
            {path: '/curriculum', name: 'Curriculum', component: Curriculum}
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
      next({ name: "Login" });
    } else if (store.state.user.token && to.meta.isGuest) {
      next({ name: "Class Scheduler" });
    } else {
      next();
    }
  });

export default router;