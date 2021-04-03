import auth from './modules/auth';
import layout from './modules/layout';
import project from './modules/project';

export default new Vuex.Store({
    modules: {
        auth,
        layout,
        project
    }
});
