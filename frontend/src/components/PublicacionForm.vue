<template>
  <div class="publicacion-container">
    <h1>📢 Crear Publicación</h1>

    <form @submit.prevent="crearPublicacion" class="publicacion-form">
      <label for="titulo">Título</label>
      <input type="text" id="titulo" v-model="titulo" required />

      <label for="descripcion">Descripción</label>
      <textarea id="descripcion" v-model="descripcion" rows="3" required></textarea>

      <button type="submit">📌 Publicar</button>
    </form>

    <p v-if="mensaje" class="success">{{ mensaje }}</p>
    <p v-if="error" class="error">{{ error }}</p>

    <button @click="$router.push('/publicaciones')" class="volver-btn">🔙 Volver a Publicaciones</button>
  </div>
</template>

<script>
import axiosInstance from '@/axios';

export default {
  name: 'PublicacionForm',
  data() {
    return {
      titulo: '',
      descripcion: '',
      mensaje: '',
      error: ''
    };
  },
  methods: {
    async crearPublicacion() {
      try {
        const userId = localStorage.getItem("user_id"); // Recuperar el ID del usuario logueado

        // Verificar si el user_id es correcto antes de enviarlo
        console.log("Enviando publicación con user_id:", userId);

        if (!userId) {
          this.error = "Error: No se encontró el ID del usuario logueado";
          return;
        }

        const response = await axiosInstance.post("create-publication", {
          user_id: userId,
          titulo: this.titulo,
          descripcion: this.descripcion
        });

        console.log("Respuesta del backend:", response.data);
        this.mensaje = response.data.message;
        this.titulo = "";
        this.descripcion = "";
      } catch (err) {
        console.error("Error en la petición:", err.response);
        this.error = err.response?.data?.message || "Error al crear la publicación";
      }
    }
  }
};
</script>

<style scoped>
/* Contenedor principal con Flexbox */
.publicacion-container {
  background-color: #000;
  color: white;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}

/* Título */
h1 {
  font-size: 2rem;
  margin-bottom: 1.5rem;
  color: #ff69b4;
}

/* Formulario */
.publicacion-form {
  display: grid;
  gap: 1rem;
  background-color: #1a1a1a;
  padding: 2rem;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 4px 6px rgba(255, 105, 180, 0.3);
}

/* Estilos de inputs y textarea */
.publicacion-form label {
  font-weight: bold;
  color: #ff69b4;
}

.publicacion-form input,
.publicacion-form textarea {
  background-color: #333;
  border: none;
  color: white;
  padding: 10px;
  border-radius: 5px;
  font-size: 1rem;
  outline: none;
}

.publicacion-form input:focus,
.publicacion-form textarea:focus {
  border: 2px solid #ff69b4;
}

/* Botón de publicar */
.publicacion-form button {
  background-color: #ff69b4;
  color: white;
  font-size: 1rem;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease-in-out;
}

.publicacion-form button:hover {
  background-color: #e0579b;
}

/* Mensajes */
.success {
  color: #4caf50;
  margin-top: 1rem;
}

.error {
  color: #ff6b6b;
  margin-top: 1rem;
}

/* Botón de volver */
.volver-btn {
  background-color: transparent;
  color: #ff69b4;
  font-size: 1rem;
  padding: 10px;
  border: 1px solid #ff69b4;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 1rem;
  transition: background 0.3s ease-in-out;
}

.volver-btn:hover {
  background-color: #ff69b4;
  color: white;
}
</style>
