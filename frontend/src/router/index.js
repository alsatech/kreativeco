import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import Publicaciones from '../components/Publicaciones.vue';
import PublicacionForm from '../components/PublicacionForm.vue';

const routes = [
  { path: '/', name: 'Login', component: Login },
  { path: '/publicaciones', name: 'Publicaciones', component: Publicaciones },
  { path: '/publicacion/nueva', name: 'NuevaPublicacion', component: PublicacionForm },
  { path: '/publicacion/editar/:id', name: 'EditarPublicacion', component: PublicacionForm, props: true },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
