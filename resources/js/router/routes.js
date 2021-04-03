// Lazy load các trang bằng cách () => import('')

export default [
    {
        path: '/login',
        name: 'login',
        component: () => import('../pages/Login/LoginIndex')
    },
    {
        path: '/forget-password',
        name: 'forgetPassword',
        component: () => import('../pages/ForgetPassword/ForgetPasswordIndex')
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        component: () => import('../pages/ResetPassword/ResetPasswordIndex')
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../pages/Register/RegisterIndex')
    },
    {
        path: '/verify-email',
        name: 'verifyEmail',
        component: () => import('../pages/VerifyEmail/VerifyEmailIndex')
    },
    {
        path: '/backend',
        name: 'backend',
        component: () => import('../pages/Backend/BackendIndex'),
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: () => import('../pages/Backend/Dashboard/DashboardIndex')
            },
            {
                path: 'profile',
                name: 'profile',
                component: () => import('../pages/Backend/Profile/ProfileIndex')
            },
            {
                path: 'app',
                name: 'app',
                component: () => import('../pages/Backend/App/AppIndex')
            },
            {
                path: 'login-log',
                name: 'loginLog',
                component: () => import('../pages/Backend/LoginLog/LoginLogIndex')
            }
        ]
    },
    {
        path: '*',
        name: 'NotFound',
        component: () => import('../pages/NotFound/NotFoundIndex')
    }
];
