<template>
  <div class="login-container">
    <div class="logo">K</div>
    <h1>Iniciar Sesión</h1>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="email">Correo:</label>
        <input type="email" v-model="email" required />
      </div>
      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" v-model="password" required />
      </div>
      <button type="submit">Ingresar</button>
    </form>
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

        this.$router.push('/publicaciones'); 
      } catch (err) {
        this.error = err.response?.data?.message || 'Error en la petición';
      }
    }
  }

};
</script>


<style scoped>

.login-container {
  background-color: #000;
  color: #fff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}


.logo {
  font-size: 6rem;
  color: #ff69b4; 
  margin-bottom: 1rem;
}


.form-group {
  margin-bottom: 1rem;
}

input[type="email"],
input[type="password"] {
  padding: 0.5rem;
  border: none;
  border-radius: 4px;
  width: 100%;
  max-width: 300px;
}

button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  background-color: #9c215f; 
  color: #fff;
  cursor: pointer;
}

button:hover {
  opacity: 0.9;
}

.error {
  color: #ff6b6b;
  margin-top: 1rem;
}
</style>
