// Lazy load các trang bằng cách () => import('')

export default [
    {
        path: '/login',
        name: 'login',
        component: () => import('../pages/Login/LoginIndex.vue')
    },
    {
        path: '/forget-password',
        name: 'forgetPassword',
        component: () => import('../pages/ForgetPassword/ForgetPasswordIndex.vue')
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        component: () => import('../pages/ResetPassword/ResetPasswordIndex.vue')
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../pages/Register/RegisterIndex.vue')
    },
    {
        path: '/verify-email',
        name: 'verifyEmail',
        component: () => import('../pages/VerifyEmail/VerifyEmailIndex.vue')
    },
    {
        path: '/backend',
        name: 'backend',
        component: () => import('../pages/Backend/BackendIndex.vue'),
        children: [
            {
                path: 'profile',
                name: 'profile',
                component: () => import('../pages/Backend/Profile/ProfileIndex.vue')
            },
            {
                path: 'app',
                name: 'app',
                component: () => import('../pages/Backend/App/AppIndex.vue')
            },
            {
                path: 'login-log',
                name: 'loginLog',
                component: () => import('../pages/Backend/LoginLog/LoginLogIndex.vue')
            }
        ]
    },
    {
        path: '*',
        name: 'NotFound',
        component: () => import('../pages/NotFound/NotFoundIndex.vue')
    }
];
