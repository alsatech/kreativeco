
<template>
  <div class="auth-container publicaciones-container">
    <h1 class="titulo">📢 Publicaciones</h1>


    <div class="acciones">
      <button @click="irNuevaPublicacion">➕ Crear Nueva Publicación</button>
    </div>

    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="loading" class="cargando">Cargando publicaciones...</div>
    <div v-else class="listado">
      <PublicationCard
        v-for="pub in publicaciones"
        :key="pub.id"
        :publication="pub"
        @delete="eliminarPublicacion"
      />
    </div>
  </div>
</template>
<script>
import axiosInstance from '@/axios';
import PublicationCard from '@/components/PublicationCard.vue';
import Swal from 'sweetalert2';


export default {
  name: 'Publicaciones',
  components: {
    PublicationCard
  },
  data() {
    return {
      publicaciones: [],
      error: '',
      loading: false,
      user: null
    };
  },
  methods: {
    async obtenerPublicaciones() {
      this.loading = true;
      try {
        const response = await axiosInstance.get('publications');
        console.log("📢 Datos recibidos:", response.data);
        this.publicaciones = response.data.publications || [];
      } catch (err) {
        this.error = err.response?.data?.message || "Error al cargar publicaciones";
      } finally {
        this.loading = false;
      }
    },
    irNuevaPublicacion() {
      this.$router.push('/publicacion/nueva');
    },
    async eliminarPublicacion(id) {
      const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      });

      if (!result.isConfirmed) return;

      try {
        const token = localStorage.getItem("token");
        const response = await axiosInstance.delete('publications', {
          headers: { Authorization: `Bearer ${token}` },
          data: { id }
        });
        
        await Swal.fire({
          title: '¡Eliminado!',
          text: response.data.message || 'La publicación se eliminó correctamente.',
          icon: 'success',
          confirmButtonText: 'Aceptar'
        });
        
        
        this.publicaciones = this.publicaciones.filter(pub => pub.id !== id);
      } catch (err) {
        console.error("Error al eliminar la publicación:", err.response);
        Swal.fire({
          title: 'Error',
          text: err.response?.data?.message || "Error al eliminar la publicación",
          icon: 'error',
          confirmButtonText: 'Aceptar'
        });
      }
    },
    async logout() {
        try {
            await axiosInstance.post("logout");
        } catch (err) {
            console.error("Error en logout:", err.response);
        }
        localStorage.removeItem("token");
        localStorage.removeItem("user_id"); 
        this.$router.push("/login"); 
    }

  },
  mounted() {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      this.user = JSON.parse(userStr);
    }
    this.obtenerPublicaciones();
  },
};
</script>


