import auth from './modules/auth.js';
import layout from './modules/layout.js';
import project from './modules/project.js';

export default new Vuex.Store({
    modules: {
        auth,
        layout,
        project
    }
});
