<template>
    <div class="publication-card">
      <h2>{{ publication.titulo }}</h2>
      <p>{{ publication.descripcion }}</p>
      <small>📅 {{ formatFecha(publication.created_at) }}</small>
      <br />
      <small>👤 {{ publication.user_name }} - {{ publication.user_role }}</small>
      <br />
      <button v-if="showDelete" @click="$emit('delete', publication.id)">❌ Eliminar</button>
    </div>
  </template>
  
  <script>
  export default {
    name: 'PublicationCard',
    props: {
      publication: {
        type: Object,
        required: true
      },
      showDelete: {
        type: Boolean,
        default: true
      }
    },
    methods: {
      formatFecha(fecha) {
        return new Date(fecha).toLocaleString();
      }
    }
  };
  </script>
  
  <style scoped>
  .publication-card {
    border: 1px solid #ff69b4;
    padding: 1rem;
    border-radius: 5px;
    background-color: rgba(255, 105, 180, 0.1);
    transition: transform 0.3s;
  }
  .publication-card:hover {
    transform: scale(1.05);
  }
  button {
    margin-top: 0.5rem;
    padding: 0.3rem 0.6rem;
    background-color: #ff4d4d;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
  }
  button:hover {
    background-color: #cc0000;
  }
  </style>
  