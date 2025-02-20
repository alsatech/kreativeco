<template>
  <div class="auth-container login-container">
    <div class="logo">K</div>
    <h1 class="titulo">Iniciar Sesión</h1>
    
    <div class="input-container">
      <form @submit.prevent="login" class="auth-form">
        <div class="form-group">
          <label for="email">Correo:</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="form-group">
          <label for="password">Contraseña:</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <button type="submit" class="btn">Ingresar</button>
      </form>
      <p class="info">
      ¿No tienes cuenta? 
      <router-link to="/register">Regístrate</router-link>
      </p>
    </div>
    
    <p v-if="error" class="error">{{ error }}</p>

  </div>
</template>


<script>
import axiosInstance from '@/axios';

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await axiosInstance.post('login', {
          email: this.email,
          password: this.password
        });
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user_id', response.data.user.id);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        this.$router.push('/publicaciones');
      } catch (err) {
        this.error = err.response?.data?.message || 'Error en la petición';
      }
    }
  }
};
</script>
