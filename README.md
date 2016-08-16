# IT-Care@HealUQ - Aplicación web de apoyo para el profesional de la salud en el cuidado de las personas con heridas.

Repositorio que contendrá todos ficheros y directorios pertenecientes a la aplicación web del trabajo de grado titulado "Aplicación web de apoyo para el profesional de la salud en el cuidado de las personas con heridas" del estudiante Andrés David Montoya Aguirre

La aplicación web se encuentra estable para uso en producción, los parámetros de configuración a tener en cuenta son los siguientes:

> * Configuración del archivo /application/config/config.php
> * Configuración del archivo /application/config/database.php
> * Ejecución del script SQL encargado de crear y poblar la base de datos.

La totalidad de funciones existentes en esta versión son las siguientes:

* Inicio de sesión en la aplicación.
* Registro de usuarios nuevos.
* Editar información de un usuario por parte de un administrador.
* Eliminar un usuario por parte de un administrador.
* Habilitar un usuario por parte de un administrador.
* Inhabilitar un usuario por parte de un administrador.
* Listar todos los usuarios paginados.
* Listar las localizaciones anatómicas registradas.
* Listar los tipos de herida.
* Listar los factores de riesgo.
* Listar las actividades.
* Visualizar los tipos de herida registrados en el sistema.
* Registrar nuevos tipos de herida.
* Editar tipos de herida existentes.
* Eliminar tipos de herida que no sean necesarios.
* Visualizar los factores de riesgo registrados en el sistema.
* Ingresar nuevos factores de riesgo.
* Modificar factores de riesgo existentes en el sistema.
* Eliminar factores de riesgo innecesarios.
* Visualizar las actividades registradas en el sistema.
* Ver la relación de una actividad con los tipos de herida.
* Ver la relación de una actividad con los factores de riesgo.
* Registrar nuevas actividades.
* Editar actividades registradas en el sistema.
* Eliminar actividades existentes.
* Ordenar las actividades relacionadas con un tipo de herida (arrastrando y soltando, solo soporte para computadores portátiles y de escritorio).
* Ver las actividades sugeridas por la aplicación de una situación de enfermería, es decir, proceder a seleccionar la localización anatómica, luego seleccionar el tipo de herida, luego seleccionar los factores de riesgo que presenta el paciente y por último visualizar las actividades sugeridas de acuerdo a la selección de los anteriores elementos.
* Registro de pacientes nuevos al usuario que ha iniciado sesión.
* Visualización de los pacientes del usuario que ha iniciado sesión.
* Adición de situación de enfermería a un paciente.
* Adición de observación e imagen en una situación de enfermería de un paciente.
* Ver el historial de situaciones de enfermería de un paciente.
* Exportar a formato .docx (Word) el historial completo de un paciente.
