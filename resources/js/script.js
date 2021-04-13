import './plugins/index.js';
import './components/index.js';

import mixin from './mixin/index.js';
import router from './router/index.js';
import store from './store/index.js';
import App from './App.vue';

Vue.mixin(mixin);

// Khởi tạo Vue
new Vue({
    router,
    store,
    ...App
});
