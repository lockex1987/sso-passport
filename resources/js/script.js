import './plugins';
import './components';

import mixin from './mixin';
import router from './router/index';
import store from './store/index';
import App from './App';

Vue.mixin(mixin);

// Khởi tạo Vue
new Vue({
    router,
    store,
    ...App
});
