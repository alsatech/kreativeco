<template>
  <div class="publicaciones-container">
    <h1 class="titulo">📢 Publicaciones</h1>

    <div class="acciones">
      <button @click="irNuevaPublicacion">➕ Crear Nueva Publicación</button>
    </div>

    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="loading" class="cargando">Cargando publicaciones...</div>

    <div v-else class="listado">
      <div v-for="pub in publicaciones" :key="pub.id" class="publicacion">
        <h2>{{ pub.titulo }}</h2>
        <p>{{ pub.descripcion }}</p>
        <small>📅 {{ formatFecha(pub.created_at) }}</small>
        <br />
        <small>👤 {{ pub.user_name }} - {{ pub.user_role }}</small>
        <br />
        <button @click="eliminarPublicacion(pub.id)" class="eliminar">❌ Eliminar</button>
      </div>

    </div>
  </div>
</template>

<script>
import axiosInstance from '@/axios';

export default {
  name: 'Publicaciones',
  data() {
    return {
      publicaciones: [],
      error: '',
      loading: false,
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
    formatFecha(fecha) {
      return new Date(fecha).toLocaleString();
    },
    async eliminarPublicacion(id) {
      if (!confirm("¿Seguro que deseas eliminar esta publicación?")) return;

      try {
        const token = localStorage.getItem("token");
        const response = await axiosInstance.delete('publications', {
          headers: { Authorization: `Bearer ${token}` },
          data: { id }
        });

        console.log("🗑 Publicación eliminada:", response.data);
        alert(response.data.message);
        
        this.publicaciones = this.publicaciones.filter(pub => pub.id !== id);
      } catch (err) {
        console.error("❌ Error al eliminar la publicación:", err.response);
        alert(err.response?.data?.message || "Error al eliminar la publicación");
      }
    },
  },
  mounted() {
    this.obtenerPublicaciones();
  },
};
</script>

<style scoped>

.publicaciones-container {
  background-color: #000;
  color: #fff;
  min-height: 100vh;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
}


.titulo {
  font-size: 2rem;
  color: #ff69b4; 
  margin-bottom: 1rem;
}


.acciones {
  margin-bottom: 1rem;
}

button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  background-color: #ff69b4;
  color: #fff;
  cursor: pointer;
}

button:hover {
  opacity: 0.9;
}


.listado {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1rem;
  width: 100%;
  max-width: 900px;
}

.publicacion {
  border: 1px solid #ff69b4;
  padding: 1rem;
  border-radius: 5px;
  background-color: rgba(255, 105, 180, 0.1);
  transition: transform 0.3s;
}

.publicacion:hover {
  transform: scale(1.05);
}


.error {
  color: red;
}

.cargando {
  font-size: 1.2rem;
}
</style>
