import routes from './routes.js';
import checkAuthentication from './checkAuthentication.js';

const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach(checkAuthentication);

export default router;
