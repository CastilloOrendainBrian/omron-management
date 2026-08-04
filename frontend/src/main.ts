import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { VueQueryPlugin } from '@tanstack/vue-query'

import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import './config/yup'

import App from './App.vue'
import router from './router'

const app = createApp(App)

router.onError((error) => {
  // eslint-disable-next-line no-console
  console.error('[router] navigation error:', error)
})

app.use(createPinia())
app.use(router)
app.use(VueQueryPlugin)
app.use(Toast)

app.mount('#app')
