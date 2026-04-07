import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './cms/App.vue'
import router from './cms/router'
import '../css/app.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.mount('#app')