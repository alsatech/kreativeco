<template>
  <div class="auth-container publicacion-container">
    <h1 class="titulo">Crear Publicación</h1>

    <form @submit.prevent="crearPublicacion" class="publicacion-form">
      <label for="titulo">Título</label>
      <input type="text" id="titulo" v-model="titulo" required />

      <label for="descripcion">Descripción</label>
      <textarea id="descripcion" v-model="descripcion" rows="3" required></textarea>

      <button type="submit">Publicar</button>
    </form>

    <button @click="$router.push('/publicaciones')" class="volver-btn">
      Volver a Publicaciones
    </button>
  </div>
</template>

<script>
import axiosInstance from '@/axios';
import Swal from 'sweetalert2';

export default {
  name: 'PublicacionForm',
  data() {
    return {
      titulo: '',
      descripcion: ''
    };
  },
  methods: {
    async crearPublicacion() {
      try {
        const userId = localStorage.getItem("user_id");
        if (!userId) {
          Swal.fire({
            title: "Error",
            text: "No se encontró el ID del usuario logueado",
            icon: "error",
            confirmButtonText: "Aceptar"
          });
          return;
        }

        const response = await axiosInstance.post("publications/create", {
          titulo: this.titulo,
          descripcion: this.descripcion
        }, {
          headers: {
            "Authorization": `Bearer ${localStorage.getItem("token")}`,
            "Content-Type": "application/json"
          }
        });

        console.log("Respuesta del backend:", response.data);

        Swal.fire({
          title: "Publicación Creada",
          text: "Tu publicación ha sido creada exitosamente.",
          icon: "success",
          confirmButtonText: "Aceptar"
        }).then(() => {
          this.titulo = "";
          this.descripcion = "";
          this.$router.push("/publicaciones");
        });

      } catch (err) {
        console.error("Error en la petición:", err.response);
        Swal.fire({
          title: "Error",
          text: err.response?.data?.message || "Error al crear la publicación",
          icon: "error",
          confirmButtonText: "Aceptar"
        });
      }
    }
  }
};
</script>
