# Biblioteca API

## Descripción del Proyecto

Esta es una API REST diseñada para gestionar un sistema de biblioteca. Permite gestionar recursos como libros y usuarios, incluyendo operaciones básicas como la creación, actualización, eliminación y consulta de libros y usuarios. El proyecto utiliza Symfony como framework y SQLite como base de datos.

## Tecnologías Utilizadas

- **Symfony**: Framework PHP para desarrollar la API REST.
- **SQLite**: Base de datos ligera y embebida utilizada para almacenar los datos de los libros y usuarios.
- **Doctrine ORM**: Herramienta de mapeo objeto-relacional utilizada para interactuar con la base de datos.
- **PHPUnit**: Herramienta para ejecutar pruebas unitarias y de integración.
  
## Instrucciones de Instalación

### Requisitos Previos

1. PHP >= 8.0
2. Composer (gestor de dependencias para PHP)
3. Symfony CLI (para ejecutar el servidor de desarrollo)

### Pasos para la instalación

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/usuario/library-api.git
   cd library-api

2. **Instala las dependencias utilizando Composer**:
    ```bash
    composer install
    ```

3. **Crea un archivo .env.local basado en el archivo .env y configura las credenciales de tu base de datos SQLite. La configuración para SQLite es la siguiente:**:
    ```ini
    DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
    ```

4. **Ejecuta las migraciones para crear las tablas en la base de datos**:
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

5. **(Opcional) Si quieres poblar la base de datos con datos de prueba, puedes usar un fixture**:
    ```bash
    php bin/console doctrine:fixtures:load
    ```

6. **Inicia el servidor de desarrollo con Symfony CLI:**:
    ```bash
    symfony server:start
    ```

La API estará disponible en `http://127.0.0.1:8000`.

## Ejecución de pruebas

Para ejecutar las pruebas unitarias y de integración, utiliza el siguiente comando:

```bash
php bin/phpunit
```

## Uso de la API

La API REST está diseñada para gestionar un sistema de biblioteca con dos recursos principales: **Libros** y **Usuarios**. A continuación se detallan los endpoints disponibles:

### **Libros**

- **`GET /libros`**  
  Devuelve la lista de todos los libros registrados en la base de datos.

- **`POST /libros`**  
  Añade un nuevo libro a la biblioteca. Los parámetros requeridos son:
  - `title`: Título del libro.
  - `author`: Autor del libro.
  - `genre`: Género del libro.
  - `year`: Año de publicación del libro.

- **`PUT /libros/{id}`**  
  Actualiza la información de un libro existente. Debes especificar el `id` del libro y puedes modificar los siguientes campos:
  - `title`: Nuevo título del libro.
  - `author`: Nuevo autor del libro.
  - `genre`: Nuevo género del libro.
  - `year`: Nuevo año de publicación.

- **`DELETE /libros/{id}`**  
  Elimina un libro de la base de datos utilizando su `id`.

### **Usuarios**

- **`GET /usuarios`**  
  Devuelve la lista de todos los usuarios registrados en el sistema.

- **`POST /usuarios`**  
  Añade un nuevo usuario al sistema. Los parámetros requeridos son:
  - `name`: Nombre del usuario.
  - `email`: Correo electrónico del usuario.
  - `age`: Edad del usuario.

- **`DELETE /usuarios/{id}`**  
  Elimina un usuario del sistema utilizando su `id`.

### Herramientas recomendadas

Puedes probar estos endpoints utilizando herramientas como [Postman](https://www.postman.com/) o [cURL](https://curl.se/).  
Asegúrate de configurar los encabezados adecuados (por ejemplo, `Content-Type: application/json`) cuando realices peticiones `POST` o `PUT`.
