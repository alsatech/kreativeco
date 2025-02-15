# kreativeco


## Capturas de pantalla

### 📌 Login
![Login](assets/login.png)

### 📌 Publicaciones
![Publicaciones](assets/publicaciones.png)

### 📌 Crear publicacion
![Dashboard](assets/endpointfront.png)


### 📌 TESTING ENDPOINTS
![Dashboard](assets/endpointI.png)
### 📌 TESTING ENDPOINTS
![Dashboard](assets/endpointII.png)
### 📌 TESTING
![Dashboard](assets/endpoint.png)

# 📘 Documentación de la API Kreativeco

## 📌 Base URL:
http://localhost/kreative/public/api.php/

---

## **🔹 1️⃣ Registro de Usuario**
- **Método:** `POST`
- **URL:** `/register`
- **Descripción:** Crea un nuevo usuario en el sistema.
- **Cuerpo de la petición (JSON):**
- - **Respuesta HTTP:**
  - ✅ `201 Created` - Usuario registrado correctamente.
  - ❌ `400 Bad Request` - Datos faltantes o incorrectos.
  - ❌ `409 Conflict` - El correo ya está registrado.

---

## **🔹 2️⃣ Inicio de Sesión (Login)**
- **Método:** `POST`
- **URL:** `/login`
- **Descripción:** Inicia sesión y devuelve un token JWT.
- **Cuerpo de la petición (JSON):**
- - **Respuesta HTTP:**
  - ✅ `200 OK` - Inicio de sesión exitoso. Devuelve un token JWT.
  - ❌ `401 Unauthorized` - Credenciales incorrectas.

---

## **🔹 3️⃣ Crear una Publicación**
- **Método:** `POST`
- **URL:** `/create-publication`
- **Descripción:** Crea una nueva publicación (requiere autenticación).
- **Encabezados:**
- - **Respuesta HTTP:**
  - ✅ `201 Created` - Publicación creada con éxito.
  - ❌ `401 Unauthorized` - Token no válido o no proporcionado.
  - ❌ `403 Forbidden` - Usuario sin permisos para crear publicaciones.

---

## **🔹 4️⃣ Obtener todas las Publicaciones**
- **Método:** `GET`
- **URL:** `/publications`
- **Descripción:** Obtiene todas las publicaciones disponibles.
- **Encabezados:**
- **Respuesta HTTP:**
  - ✅ `200 OK` - Devuelve la lista de publicaciones.
  - ❌ `401 Unauthorized` - Token no válido o no proporcionado.

---

## **🔹 5️⃣ Actualizar una Publicación**
- **Método:** `PUT`
- **URL:** `/update-publication`
- **Descripción:** Modifica el contenido de una publicación.
- **Encabezados:**
- - **Respuesta HTTP:**
  - ✅ `200 OK` - Publicación actualizada con éxito.
  - ❌ `400 Bad Request` - Datos faltantes o incorrectos.
  - ❌ `403 Forbidden` - Usuario sin permisos para actualizar publicaciones.

---

## **🔹 6️⃣ Eliminar una Publicación**
- **Método:** `DELETE`
- **URL:** `/publications`
- **Descripción:** Elimina una publicación (requiere permisos de administrador).
- **Encabezados:**
# 📌 Roles y Permisos
| Rol         | Acceso | Consulta | Crear | Actualizar | Eliminar |
|-------------|--------|----------|--------|------------|----------|
| **Básico**   | ✅ Sí | ❌ No | ❌ No | ❌ No | ❌ No |
| **Medio**    | ✅ Sí | ✅ Sí | ❌ No | ❌ No | ❌ No |
| **Medio Alto** | ✅ Sí | ✅ Sí | ✅ Sí | ❌ No | ❌ No |
| **Alto Medio** | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí | ❌ No |
| **Alto**     | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí |

---




