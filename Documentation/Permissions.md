#Permisos

El sistema utiliza como base Bouncer sin embargo no se utiliza en código directamente esa librería.

##Estructura de tablas 

###Tabla roles

Esta tabla posee los nombres de los roles así como su creador y tipo.

El creador especifica en qué marca/company/brand aparecerán estos permisos, los mismos se heredarán a todos los elementos hijos que estos posean.

El tipo especificará desde qué nivel del sistema estos roles podrán ser seleccionados.

- `name` (string): slug del rol en el sistema
- `title` (string): Nombre del rol
- `owner_type` (null|string): 
    - `null` : El creador de mismo es Gafafit
    - `string` : El creador es un App\Models\Company, App\Models\Brand o App\Models\Location
- `owner_id` (null|int) : 
    - `null` : El creador es Gafafit
    - `int` : El id del creador
- `type` (string): Especifica el nivel máximo en el que aparecerá este rol. Sus posibles valores son:
    - `gafafit`
    - `company`
    - `brand`
    - `location`
    
###Tabla abilities

En nuestro sistema nunca daremos un permiso específico con `entity_id` ya que eso lo sabremos dependiendo en dónde se asignó determinado rol a un usuario.

Todas las habilidades `null` son habilidades de Gafafit.

- `name` (string): slug del permiso en el sistema
- `title` (string): Nombre del permiso
- `entity_type` (null|string): 
    - `null` : El permiso es para realizar la tarea del `name` a nivel Gafafit
    - `string` : El permiso es para realizar la tarea del `name`  en una App\Models\Company, App\Models\Brand o App\Models\Location
- `entity_id` (null|int) : 
    - `null` : El permiso es para cualquier `entity_type`
    - `int` : El permiso es para el `entity_type` con id que especifiquemos.
    
###Tabla permissions

Establece qué habilidades posee cada rol.

- `ability_id` (int): Id de la habilidad
- `entity_type` (string): 
    - `string` : Nombre del modelo que tendra la habilidad
- `entity_id` (int) : 
    - `int` : Id del modelo que tendrá la habilidad
- `forbidden` (int) : Establece si ese rol olvidó ya esa abilidad. Es como si esa linea no existiera.

###Tabla assigned_roles

Establece que usuario tiene un rol determinado y en qué nivel.

- `role_id` (int): Id del rol
- `entity_type` (string): 
    - `string` : Modelo del tipo de usuario
- `entity_id` (int) : 
    - `int` : Id del modelo que tendrá el rol
- `assigned_type` (null|string): 
    - `null` : El rol se asigna a nivel Gafafit
    - `string` : El rol se asigna a una App\Models\Company, App\Models\Brand o App\Models\Location
- `assigned_id` (null|int) : 
    - `null` : El rol se asigna a nivel Gafafit
    - `int` : El rol es para el `entity_type` con id que especifiquemos.

    
    
##Accesos

Para poder acceder a cualquier nivel se debe de tener el permiso `access`. El sistema posee 4 niveles:
- GafaFit
- Company
- Brand
- Location

##Permiso completo de nivel
Para cada nivel existe un permiso `all` que permite acceder a cualquier característica del nivel.

##Ejemplos:

### Acceso a Gafafit:
Para acceder a nivel Gafafit el usuario debe de tener 
un rol asignado que posea el abilitie `access` sin ningun `entity_type` ni `entity_id`

Un usuario puede tener el permiso `all` a nivel GafaFit, eso querrá decir que tiene todos los permisos de ese nivel

### Acceso a Company

Para acceder a una company se debe de tener el permiso `access` con `entity_type` en App\Models\Company\Company.

Un usuario puede tener el permiso `all` a nivel Company, eso querrá decir que tiene todos los permisos de ese nivel

El acceso se dará si se cumplen estos casos:
 - rol asignado con `assigned_type` y `assigned_id` en `null`
 - rol asignado con `assigned_type` y `assigned_id` con el valor de la company en cuestión.
 - rol asignado con `assigned_type` y `assigned_id` con el valor de un brand hijo.
 - rol asignado con `assigned_type` y `assigned_id` con el valor de un location hijo.

### Acceso a Marca

Para acceder a un brand se debe de tener el permiso `access` con `entity_type` en App\Models\Brand\Brand.

Un usuario puede tener el permiso `all` a nivel Brand, eso querrá decir que tiene todos los permisos de ese nivel

El acceso se dará si se cumplen estos casos:
 - rol asignado con `assigned_type` y `assigned_id` en `null`
 - rol asignado con `assigned_type` y `assigned_id` con el valor de la compañia padre.
 - rol asignado con `assigned_type` y `assigned_id` con el valor del brand en cuestión.
 - rol asignado con `assigned_type` y `assigned_id` con el valor de un location hijo.

### Acceso a Location

Para acceder a una location se debe de tener el permiso `access` con `entity_type` en App\Models\Location\Location.

Un usuario puede tener el permiso `all` a nivel location, eso querrá decir que tiene todos los permisos de ese nivel

El acceso se dará si se cumplen estos casos:
 - rol asignado con `assigned_type` y `assigned_id` en `null`
 - rol asignado con `assigned_type` y `assigned_id` con el valor de la compañia padre.
 - rol asignado con `assigned_type` y `assigned_id` con el valor del brand padre.
 - rol asignado con `assigned_type` y `assigned_id` con el valor de la location en cuestión.
