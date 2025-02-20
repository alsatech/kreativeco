<template>
  <div class="auth-container register-container">
    <h1 class="titulo">Registro de Usuario</h1>
    <div class="input-container">
      <form @submit.prevent="registerUser" class="auth-form">
      <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" v-model="nombre" required placeholder="Ingresa tu nombre" />
      </div>
      <div class="form-group">
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" v-model="apellido" required placeholder="Ingresa tu apellido" />
      </div>
      <div class="form-group">
        <label for="email">Correo:</label>
        <input type="email" id="email" v-model="email" required placeholder="correo@ejemplo.com" />
      </div>
      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" v-model="password" required placeholder="Mínimo 6 caracteres" minlength="6" />
      </div>
      <div class="form-group">
        <label for="rol">Rol:</label>
        <select id="rol" v-model="rol" required>
          <option disabled value="">Seleccione un rol</option>
          <option value="Básico">Básico</option>
          <option value="Medio">Medio</option>
          <option value="Medio Alto">Medio Alto</option>
          <option value="Alto Medio">Alto Medio</option>
          <option value="Alto">Alto</option>
        </select>
      </div>
      <button type="submit" class="btn">Registrar</button>
    </form>
    <p class="info">
      ¿Ya tienes cuenta? <router-link to="/">Inicia Sesión</router-link>
    </p>

    </div>
    <p v-if="error" class="error">{{ error }}</p>
    <p v-if="mensaje" class="success">{{ mensaje }}</p>
  </div>
</template>

<script>
import axiosInstance from '@/axios';

export default {
  name: 'Register',
  data() {
    return {
      nombre: '',
      apellido: '',
      email: '',
      password: '',
      rol: '',
      error: '',
      mensaje: ''
    }
  },
  methods: {
    async registerUser() {
      if (!this.nombre || !this.apellido || !this.email || !this.password || !this.rol) {
        this.error = 'Por favor, completa todos los campos.';
        return;
      }
      
      try {
        const response = await axiosInstance.post('register', {
          nombre: this.nombre,
          apellido: this.apellido,
          email: this.email,
          password: this.password,
          rol: this.rol
        });
        this.mensaje = response.data.message;
        this.nombre = this.apellido = this.email = this.password = this.rol = '';
        this.error = '';
      } catch (err) {
        this.error = err.response?.data?.message || 'Error en el registro';
      }
    }
  }
};
</script>
