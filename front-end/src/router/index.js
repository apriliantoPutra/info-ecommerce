import { createRouter, createWebHistory } from "vue-router";

// Layout
import MainLayout from "../layouts/MainLayout.vue"
import AdminLayout from "../layouts/AdminLayout.vue"

// Pages
import LoginPage from "../pages/Auth/Login.vue"
import RegisterPage from "../pages/Auth/Register.vue"
import HomePage from "../pages/Home.vue"
import ProductPage from "../pages/Product.vue"


import DashboardPage from "../pages/Admin/Dashboard.vue"
import CategoryPage from "../pages/Admin/Category.vue"
import UserPage from "../pages/Admin/User.vue"
import AdminProductPage from "../pages/Admin/Product.vue"

import NotFoundPage from "../pages/NotFound.vue"

const routes= [
    {
        path: '/',
        component: MainLayout,
        children: [
            {
                path: "",
                name: "Home",
                component: HomePage
            },
            {
                path: "/products",
                name: "Products",
                component: ProductPage
            },
            
        ]
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: {requiresAuth: true, role: 'admin'},
        children: [
            {
                path: "/dashboard",
                name: "AdminDashboard",
                component: DashboardPage
            },
            {
                path: "/user",
                name: "AdminUsers",
                component: UserPage
            },
            {
                path: "/category",
                name: "AdminCategories",
                component: CategoryPage
            },
            {
                path: "/admin-products",
                name: "AdminProducts",
                component: AdminProductPage
            },
            
        ]
    },
    {
        path: '/login',
        name: 'Login',
        component: LoginPage
    },
    {
        path: '/register',
        name: 'Register',
        component: RegisterPage
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFoundPage
    }
]

const router= createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next)=> {
    const token= localStorage.getItem('token')
    const user= JSON.parse(localStorage.getItem('user'))
    if ((to.name === 'Home' || to.name === 'Login') &&  token && user){
        if(user.role === 'admin'){
            return next({name: 'AdminDashboard'})
        }

        return next()
    }

    if (to.meta.requiresAuth && !token){
        return next({
            name: 'Login',
            query: {message: 'Silakan login terlebih dahulu untuk melanjutkan.'}
        })
    }
    if(to.meta.role && user?.role !== to.meta.role){
        return next({ 
            name: 'Login',
            query: { message: 'Akses ditolak! Role kamu tidak diizinkan ke halaman ini.'}
        })
    }
    next()
})

export default router