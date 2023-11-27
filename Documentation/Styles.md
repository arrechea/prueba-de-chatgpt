#Hoja de Estilos

##Formularios regulares
Dentro de formularios normales dentro del sitio (no modales), cada página va encapsulada con un:

    <div class="card-panel radius--forms">
        (...)
    </div>

El título de la página será el h5 siguiente:
    
    <h5 class="header card-title"> (..) </h5>

Cada sección que se quiera agregar para organizar el formulario irá encapsulado con:

    <div class="card-panel panelcombos">
        (...)
    </div>
    
Cada sección puede tener un título usando un h5 sin clase precediendo a la sección.    
    
Ejemplo completo:
    
    <div class="card-panel radius--forms">
        <h5 class="header card-title">Título de la Página</h5>
        
        <h5>Título Sección 1</h5>
        <div class="card-panel panelcombos">
            (Contenido sección 1)
        </div>
        
        <h5>Título Sección 2</h5>
        <div class="card-panel panelcombos">
            (Contenido sección 2)
        </div>
    </div>
    
##Formularios dentro de modales
Para los formularios dentro de modales, se sigue un formato similar. 

Cada formulario va a ir encapsulado con 

    <div class="model--border-radius">
            (...)
    </div>   
    
Cada sección, irá encapsulada con:

      <div class="panelcombos col panelcombos_full">
              (...)
      </div>
      
Los títulos serán `h5` sin clase. El título de cada sección precede a la misma.

Ejempo:

          <div class="model--border-radius">
              <h5>Título de la Página</h5>
              
              <h5>Título Sección 1</h5>
              <div class="panelcombos col panelcombos_full">
                  (Contenido sección 1)
              </div>
              
              <h5>Título Sección 2</h5>
              <div class="panelcombos col panelcombos_full">
                  (Contenido sección 2)
              </div>
          </div>
  
##Modales de confirmación          
Para modales de confirmación (aquellos que preguntan si el usuario quiere 
realizar alguna acción) se usarán modales con clase `modaldelete` y clase 
`modal-fixed-footer`. 
Estos tendrán un elemento `<form>` rodeando la información relevante y algún 
mensaje usando `h5`. Dentro del `modal-footer` irán dos botones: uno de 
confirmación y otro de cancelación.

Ejemplo:

    <div class="modal modal-fixed-footer modaldelete">
        <div class="modal-content">
            <form id="prueba-delete-form">
                <input hidden name="id" value="1">
                <h5>¿Desea borrar esto?</h5>
            </form>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer" href="#"> 
                <i class="material-icons small">clear</i>
                Cancelar
            </a>
            <a class="modal-action modal-close waves-effect waves-green btn edit-button"
               id="prueba-delete-button">
                <i class="material-icons small">done</i>
                Eliminar
            </a>
        </div>
    </div>   
    
Con la clase `edit-button` se puede dar formato a los botones de edición. También se
puede usar la clase `save-button-footer` para dar margen al botón que acabe al lado
derecho.

Si se le pone la clase `model-delete-button` al botón de confirmación del modal (en el 
ejemplo sería el botón de eliminar) y se le da un `data-name`, donde el formato del id 
del formulario de eliminación es el siguiente: `{data-name}-delete-form`. Con esto el
botón enviará el formulario de borrado automáticamente.

Esto último solo funciona con modales de confirmación de borrado, donde el formulario
original desde dónde se llaman no esté dentro de un modal. Otros modales de
confirmación requieren atención individual.

##Extras
- Los modales no deben de tener las esquinas redondeadas. Si tienen la clase `model--border-radius`,
quitársela.

- El botón de guardar siempre va a ir en la esquina inferior derecha
