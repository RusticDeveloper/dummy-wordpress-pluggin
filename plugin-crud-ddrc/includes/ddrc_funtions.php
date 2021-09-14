<?php

/*
DDRC-comentario: añade una pagina al menu de administracion de wordpress
*/

/*Añade un hook con la funcion nativa de wordpres de add_action */
add_action( 'admin_menu', 'Admin_crud' );


/* DDRC-comentario: la siguiente funcion crea una pagina para añadirla al menu de administracion de worpress */
function Admin_crud(){
    add_menu_page(
        'Pagina de administracion del crud (solo para administrador del sitiio)', // Título pagina
        'CRUD settings', // Titulo nenú administracion
        'manage_options', // requisitos para ver el link
        'includes/Gestion_CRUD.php' // 'slug' - archivo que se muestra cuando clikamos el link
    );
}