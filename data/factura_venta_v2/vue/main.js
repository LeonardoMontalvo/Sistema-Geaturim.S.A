import {
    createApp
}
//from "./../../../dist/js/vue.334.esm-browser.prod.min.js" //prod
from "./../../../dist/js/vue.334.esm-browser.min.js" //dev

import App from './App.js'

const app = createApp(App).mount('#app')