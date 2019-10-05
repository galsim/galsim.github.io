import Vue from 'vue'
import Vuelidate from 'vuelidate'
import App from './App.vue'
import router from './router'
import store from './store'
import dateFilter from '@/filters/date.filter'
import './registerServiceWorker'
import 'materialize-css/dist/js/materialize.min'
import messagePlugin from '@/utils/message.plugin'


import firebase from 'firebase/app'
import 'firebase/auth'
import 'firebase/database'

Vue.config.productionTip = false

Vue.use(messagePlugin)
Vue.use(Vuelidate)
Vue.filter('date', dateFilter)


firebase.initializeApp({
  apiKey: "AIzaSyCkLZSMTRdOpeRLYb4qCPAfd3fjH1-LakY",
  authDomain: "vue-crm-study-56943.firebaseapp.com",
  databaseURL: "https://vue-crm-study-56943.firebaseio.com",
  projectId: "vue-crm-study-56943",
  storageBucket: "",
  messagingSenderId: "825251025447",
  appId: "1:825251025447:web:3f74cb130d58cdf1"
})

let app

firebase.auth().onAuthStateChanged(() => {
  if (!app) {
    app = new Vue({
      router,
      store,
      render: h => h(App)
    }).$mount('#app')
  }
})


