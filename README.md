-- CREAR DB --
Se puede usar el archivo cac_php.sql dentro de la carpeta sql para crear la base de datos y así evitar crearla a mano.

-- ENDPOINTS --

1. Obtener todas las películas:
   Método: GET
   URL: http://localhost:8500/api/movie.php
   Parámetros: NO

2. Obtener una película por id
   MÉTODO: GET
   URL: http://localhost:8500/api/movie.php?id=1 (reemplazar 1 por el ID a buscar)
   Parámetros: NO

3. Crear una película
   MÉTODO: POST
   URL: http://localhost:8500/api/movie.php
   Parámetros: [
   "title": string, {Required} // Título de la película
   "description": string, {Optional} // Descripción de la película
   "release_date": string, {Required} // Fecha de lanzamiento de la película
   "duration": string, {Required} // Duración de la película
   "author_id" : int, {Required} // Id del autor
   "thumbnail": File, {Required} // Miniatura de la película
   "video": File, {Required} // Video de la película
   ]

4. Actualizar una película
   MÉTODO: PUT
   URL: http://localhost:8500/api/movie.php
   Parámetros: [
   "id": int, {Required} // Id de la película
   "title": string, {Optional} // Título de la película
   "description": string, {Optional} // Descripción de la película
   "release_date": string, {Optional} // Fecha de lanzamiento de la película
   "duration": string, {Optional} // Duración de la película
   "author_id" : int, {Optional} // Id del autor
   ]

5. Eliminar una película
   MÉTODO: DELETE
   URL: http://localhost:8500/api/movie.php
   Parámetros: [
   "id": int, {Required} // Id de la película
   ]

6. Obtener todos los autores
   MÉTODO: GET
   URL: http://localhost:8500/api/author.php
   Parámetros: NO

7. Obtener un autor por id
   MÉTODO: GET
   URL: http://localhost:8500/api/author.php?id=1 (reemplazar 1 por el ID a buscar)
   Parámetros: NO

8. Eliminar un autor
   MÉTODO: DELETE
   URL: http://localhost:8500/api/author.php
   Parámetros: [
   "id": int, {Required} // Id del autor
   ]
