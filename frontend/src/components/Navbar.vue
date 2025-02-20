<template>
    <nav class="navbar">
      <div class="nav-left">
        <h1 class="logo">📢 KreativeCo</h1>
      </div>
  
      <div class="nav-right">
        <span v-if="user">Bienvenido, {{ user.nombre }} ({{ user.role }})</span>
        <button v-if="user" @click="logout" class="logout-btn">Cerrar sesión</button>
      </div>
    </nav>
  </template>
  
  <script>
  import axiosInstance from '@/axios';
  import { useRouter } from 'vue-router';
  
  export default {
    name: "Navbar",
    data() {
      return {
        user: null
      };
    },
    mounted() {
      const userStr = localStorage.getItem("user");
      if (userStr) {
        this.user = JSON.parse(userStr);
      }
    },
    methods: {
      async logout() {
        try {
          await axiosInstance.post("logout");
        } catch (err) {
          console.error("Error en logout:", err.response);
        }
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        this.$router.push("/login");
      },
    }
  };
  </script>
  
  <style scoped>
  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #222;
    padding: 15px 20px;
    color: white;
  }
  
  .nav-left .logo {
    font-size: 1.5rem;
    font-weight: bold;
  }
  
  .nav-right {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .logout-btn, .login-btn {
    background-color: crimson;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
  }
  
  .logout-btn:hover, .login-btn:hover {
    background-color: darkred;
  }
  </style>
  