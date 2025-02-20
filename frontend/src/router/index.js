import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Publicaciones from '../views/Publicaciones.vue'; 
import PublicacionForm from '../views/PublicacionForm.vue'; 

const routes = [
  { path: '/', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/publicaciones', name: 'Publicaciones', component: Publicaciones },
  { path: '/publicacion/nueva', name: 'NuevaPublicacion', component: PublicacionForm },
  { path: '/publicacion/editar/:id', name: 'EditarPublicacion', component: PublicacionForm, props: true },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

const publicPages = ['/', '/register'];

router.beforeEach((to, from, next) => {
  const authRequired = !publicPages.includes(to.path);
  const loggedIn = localStorage.getItem('token');

  if (authRequired && !loggedIn) {
    return next('/');
  }
  next();
});

export default router;



