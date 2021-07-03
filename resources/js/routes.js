import Vue from 'vue/dist/vue.min.js'
import Router from 'vue-router';

window.Vue = require('vue');
Vue.use(Router)

import dashboard from './views/Dashboard'

import e404 from './components/E404'

import usuarios from './views/Usuarios'

import configuracion from './views/Configuracion'
import login from './views/Login'
import register from './views/Register'


export default  new Router({
    routes:[
     
        {
            path: '/register',
            name: 'register',
            component: register,
            meta: {
              auth: false
            }
        },

        {
            path: '/login',
            name: 'login',
            component: login,
            meta: {
              auth: false
            }
        },

        {
            path: '/',
            name: 'dashboard',
            component: dashboard,
            meta: {
                auth: true
              },
        

        },




        {
            path: '/usuario',
            name: 'usuarios',
            component: usuarios,
            meta: {
                auth: true
              },
              beforeEnter: (to, from, next) => {
                const authUser = JSON.parse(window.localStorage.getItem('lbUser'))
                 if (authUser.data.id_role === 1) {
                   next();
                 } else {
                   next('/configuracion');
                 }
             },

        },

    
    

        {
            path: '/configuracion',
            name: 'configuracion',
            component: configuracion,
            meta: {
                auth: true
              }

        },
 



 

  {
    path: '*',
    component: e404,
},
  

    ],
    mode: 'history'

})
