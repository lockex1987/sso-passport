import routes from './routes';
import checkAuthentication from './checkAuthentication';

const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach(checkAuthentication);

export default router;
