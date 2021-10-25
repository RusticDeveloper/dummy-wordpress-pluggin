<?php

/**
 * Plugin Name:       CC MANAGEMENT
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       wp-cc-management es un plugin que permite renderizar y administrar los saberes que corape administra.
 * Version:           1.00.0
 * Requires at least: 1.00
 * Requires PHP:      7.2
 * Author:            Daniel Rivas
 * Author URI:        https://github.com/RusticDeveloper
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ddrc-crud
 * Domain Path:       /languages
 */

/*
ADMINISTRACION DE WORDPRESS CONECTA CULTURAS es un software de uso personal creado por Daniel Rivas para la implementacion 
de un crud simple dentro de wordpress
*/
//DDRC-C: los comentarios de arriba sirven para que wordpress reconosca correctamente el plugin

/* 
***************************************************************************
*****->DDRC-TODO: hacer que las tablas tengan paginacion <-********
***************************************************************************
*/


//DDRC-C: La linea de abajo sirve para llamar la clase que construira la vista para los usuarios de wordpress
require_once dirname(__FILE__) . '/Controller/shortcode.class.php';

// DDRC-C: funcion que realizara algunas comprovaciones para ser aceptada
function Activar(){
    //DDRC-C: Crea las bases de datos si aún no existen
    global $wpdb;
    $query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}android` (
        `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `Usuario_ID` int(11) NOT NULL,
        `Titulo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
        `Descripcion` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
        `Publicado` varchar(5) NOT NULL,
        `NacionalidadoPueblo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
        `TipoArchivo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
        `TagsTematicas` varchar(150) NOT NULL,
        `NombreSaber` varchar(500) NOT NULL,
        `RutaSaber` varchar(500) NOT NULL,
        PRIMARY KEY (`ID`),
        UNIQUE KEY `ID` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8 COMMENT='tabla de prueba para la coneccion con android y wordpress de'
       ";
    
    $wpdb->query($query);
    $query1 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}actions` (
        `ID`  NOT NULL,
        `Accion_Crud` varchar(100) NOT NULL,
        `Shortcode` varchar(30) NOT NULL,
        PRIMARY KEY (`ID`),
        PRIMARY KEY (`ID`),
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $wpdb->query($query1);
    
    $qn="SELECT COUNT(*) FROM `{$wpdb->prefix}actions`";
    $enum=$wpdb->query($qn);
       if($enum==0){
        $query2= "INSERT INTO `{$wpdb->prefix}actions` (`Accion_Crud`, `Shortcode`) VALUES 
        ('Crea un nuevo saber', '[DDRC id=\"1\"]'),
        ('Actualiza un saber', '[DDRC id=\"2\"]'),
        ('Elimina un saber', '[DDRC id=\"3\"]'),
        ('Muestra un saber', '[DDRC id=\"4\"]'),
        ('Muestra las naciones y pueblos que tiene el repositorio', '[DDRC id=\"5\"]'),
        ('Muestra los tipos de saberes que hay en el repositorio', '[DDRC id=\"6\"]'),
        ('Muestra los saberes filtrados por nacion y tipo de saber', '[DDRC id=\"7\"]'),
        ('Muestra un saber especifico', '[DDRC id=\"8\"]')
        ;";
        $wpdb->query($query2);
       }

    //DDRC-comentario: Crea las carpetas que guardaran los saberes
    $ruta = dirname(__FILE__) . "/../../uploads/Saberes";
    $rutaI = dirname(__FILE__) . "/../../uploads/Saberes/Imagenes";
    $rutaV = dirname(__FILE__) . "/../../uploads/Saberes/Videos";
    $rutaA = dirname(__FILE__) . "/../../uploads/Saberes/Audios";
    $rutaT = dirname(__FILE__) . "/../../uploads/Saberes/Textos";

    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
    if (!file_exists($rutaI)) {
        mkdir($rutaI, 0777, true);
    }
    if (!file_exists($rutaV)) {
        mkdir($rutaV, 0777, true);
    }
    if (!file_exists($rutaA)) {
        mkdir($rutaA, 0777, true);
    }
    if (!file_exists($rutaT)) {
        mkdir($rutaT, 0777, true);
    }
}

// DDRC-C: funcion que se activa cuando el plugin se desactiva
function Desactivar(){
    //Reinicia todos los ajustes dentro de wordpress y los reescribe
    flush_rewrite_rules();
}

// DDRC-C: Registra la funcion de activacion del boton de activar de worpress
register_activation_hook(__FILE__, 'Activar');

//DDRC-C: Registra la funcion de desactivacion del boton de antivar de worpress
register_deactivation_hook(__FILE__, 'Desactivar');

/*DDRC-C: funcion que permite poner en cola la dependencia de bootstrap JS*/
function EncolarBootstrapJS($hook){
    echo "<script>console.log('$hook');</script>";
    if ($hook != "toplevel_page_gestion_crud") {
        return;
    }
    wp_enqueue_script('bootstrapjs', plugins_url('add-ons/bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', 'EncolarBootstrapJS');

/*DDRC-C: funcion que permite poner en cola la dependencia de bootstrap CSS*/
function EncolarBootstrapCSS($hook){
     //echo "<script>console.log('$hook');</script>";
     if ($hook != "toplevel_page_gestion_crud") {
        return;
    }
    wp_enqueue_style('bootstrapcss', plugins_url('add-ons/bootstrap/css/bootstrap.min.css', __FILE__));
}

/*DDRC-C: funcion que permite poner en cola un css personalizado*/
function EncolaCustomCSS($hook){
    add_editor_style('add-ons/css/global.css');
    wp_enqueue_style('customCSS', plugins_url('add-ons/css/global.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'EncolarBootstrapCSS');
add_action('admin_enqueue_scripts', 'EncolaCustomCSS');
add_action('init', 'EncolaCustomCSS');

/* DDRC-comentario: la siguiente funcion crea el contenido de la pagina de administracion del crud */
function GestionContenido()
{
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->prefix}actions;";
    $listaCruds = $wpdb->get_results($query,ARRAY_A);

    echo "<div class='wrap'>
    <h1>" . get_admin_page_title() . "</h1><br><br>
    <p>Copie estos codigos en las paginas en las que quiera que se renderize la funcionalidad</p>
    <br><br>
    <table class='wp-list-table widefat fixes striped pages'>
        <thead>
            <th>Descripción</th>
            <th>shortcode</th>

        </thead>
        <tbody id='lista_crud'>";
    foreach ($listaCruds as $key => $value) {
        $accion = $value["Accion_Crud"];
        $shortcode = $value["Shortcode"];
        $identificacion=$value["ID"];
        echo "<tr>
           <td>$accion</td>
           <td>$shortcode</td>

           </tr>
           ";
    }
    echo '</tbody>
        </table>
        </div>
      ';
}




/*DDRC-comentario: la siguiente funcion crea una pagina para añadirla al menu de administracion de worpress */
function Admin_crud()
{
    add_menu_page(
        'Gestión paginas de saberes', // Título pagina
        'configuracion CC ', // Titulo nenú administracion
        'manage_options', // requisitos para ver el link
        //plugin_dir_url(__FILE__).'includes/adminPage.php'//this is the page path instead a slug
        'gestion_cc',  //'slug' - archivo que se muestra cuando clikamos el link
        'GestionContenido' //funcion que mostrara el contenido de la pagina de gestion
    );
}

/*DDRC-C: Usa el hook add_action para añadir una funcion,
 que generara una pagina dentro del parnel de administracion(Escritorio) de wordpress */
add_action('admin_menu', 'Admin_crud');


/*DDRC-C: para inicializar la paginaion de worpdress*/
function EncolarJS($hook){
     ?>
    <link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css'>
            <script type='text/javascript' charset='utf8' src='https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js'></script>
            <script type='text/javascript' src='../wp-content/plugins/wp-cc-management/add-ons/js/functions.js'>
    // wp_enqueue_script('customjs1', plugins_url('/add-ons/js/functions.js', __FILE__), array('jquery'));
    <?php
}
add_action('wp_head', 'EncolarJS');

/*DDRC-C: crea la interfaz frontend usando la api de wordpress para crear shortcodes
*/
function PrintShortcode($atts){
    session_start();
    // DDRC-C:instancia la clase shortcode que se creo para mostrar el front-end
        $_short = new shortCode;
    //DDRC-C:atributo que es estraido del shortcode como identificacion de la accion que se realizara
        $id=$atts['id'];
    //DDRC-C: obtencion de valores de la tabla android para compararlkos con los valores enviados desde la lista de saberes 
        global $wpdb;
        $consulta="SELECT * FROM {$wpdb->prefix}android";
        $resultado=$wpdb->get_results($consulta,ARRAY_A);
    //DDRC-C: pregunta si los botones de crear, editar, eliminar o cualquier otro de la lista de saberes han sido presionados para hacer la accion pertinente
        if (isset($_POST['Crear_Saber'])) {
            $titluo=$_POST['title_saber'];
            $abstrac=$_POST['desc_saber'];
            $shared=$_POST['public'];
            $belongTo=$_POST['NacPue'];
            $kindOf=$_POST['tipoArchivo'];
            $temas=$_POST['Temas_saber'];
            $nombre=$_FILES['saber']['name'];
            $direccion=$_FILES['saber']['tmp_name'];
            $datos=[
                'Titulo'=>$titluo,
                'Descripcion'=>$abstrac,
                'Publicado'=>$shared,
                'NacionalidadoPueblo'=>$belongTo,
                'TipoArchivo'=>$kindOf,
                'NombreSaber'=>$nombre,
                'TagsTematicas'=>$temas
            ];

            /*TODO:enviar informacion relevante para guardar el la tabla de wp_posts */
            $_short->GuardarSaber($datos,$direccion);
            
        }else if(isset($_POST['Actualizar_Saber'])) {
            $titluo=$_POST['title_saber'];
            $abstrac=$_POST['desc_saber'];
            $shared=$_POST['public'];
            $belongTo=$_POST['NacPue'];
            $kindOf=$_POST['tipoArchivo'];
            $temas=$_POST['Temas_saber'];
            $alias=$_POST['Actualizar_Saber'];
            $replazableFile=$_POST['rutaSaber'];
            if (empty($_FILES['saber']['name'])) {
                echo "no se ha seleccionado ningun archivo";
                $datos=[
                    'Titulo'=>$titluo,
                    'Descripcion'=>$abstrac,
                    'Publicado'=>$shared,
                    'NacionalidadoPueblo'=>$belongTo,
                    'TipoArchivo'=>$kindOf,
                    'TagsTematicas'=>$temas
                ];
                $idsaber=['ID'=>$alias];
                $respuesta=$_short->ActualizarSaber($datos,$idsaber,null,null,null);
            }else{
                $nombre=$_FILES['saber']['name'];
                $direccion=$_FILES['saber']['tmp_name'];
                $datos=[
                    'Titulo'=>$titluo,
                    'Descripcion'=>$abstrac,
                    'Publicado'=>$shared,
                    'NacionalidadoPueblo'=>$belongTo,
                    'TipoArchivo'=>$kindOf,
                    'NombreSaber'=>$nombre,
                    'TagsTematicas'=>$temas
                ];
                /*DDRC-C: datos para guardar en la base de datos tabla wp_posts-->para que se muestren en la biblioteca de medios*/
             $datosWordpress=[
                'post_author'=>'1',
                'post_title'=>$titluo,
                'post_status'=>'inherit',
                'comment_status'=>'open',
                'ping_status'=>'closed',
                'post_name'=>$nombre,
                'menu_order'=>'0',
                'post_type'=>'attachment',
                'comment_count'=>'0',
            ];
                $idsaber=['ID'=>$alias];
                $respuesta=$_short->ActualizarSaber($datos,$idsaber,$direccion,$replazableFile,$datosWordpress);
            }
        }else if(isset($_POST['Eliminar_Saber'])) {
            
            $alias=$_POST['Eliminar_Saber'];
            $Nombre=$_POST['title_saber'];
            $datos=[
                'ID'=>$alias
            ];
            $_short->EliminarSaber($datos,$Nombre);
            
        }else if(isset($_POST['share_saber'])) {
            $html=$_short->ConstructorVista(1,null,null,null);
                    return $html;
        }else if(isset($_POST['cancelar'])) {
            $html=$_short->ConstructorVista(4,null,null,null);
                    return $html;
            
        }
        if (isset($_POST['seleccionado'])) {
            
            $_SESSION['seleccionado']=$_POST['seleccionado'];
            $html=$_short->ConstructorVista(6,null,null,null);
            return $html;
        }
        if (isset($_POST['typoFile'])){
            
            
            $_SESSION['typoFile']=$_POST['typoFile'];
            $html=$_short->ConstructorVista(7,null,$_SESSION['seleccionado'],$_SESSION['typoFile']);
            return $html;
        }
        if (isset($_POST['selecFile'])){
            
            $_SESSION['selecFile']=$_POST['selecFile'];
            $html=$_short->ConstructorVista(8,$_SESSION['selecFile'],$_SESSION['seleccionado'],$_SESSION['typoFile']);
            return $html;
        }
        foreach ($resultado as $key => $value) {
            $index=$value['ID'];
            if (isset($_POST["UP_$index"])) {
                if($value['ID']==$_POST["UP_$index"]) {
                    $html=$_short->ConstructorVista(2,$index,null,null);
                    return $html;
                }    
            }else if (isset($_POST["DEL_$index"])) {
                if($value['ID']==$_POST["DEL_$index"]) {
                    $html=$_short->ConstructorVista(3,$index,null,null);
                    return $html;
                }    
            }
        }

    //DDRC-C: pregunta que acciona con el id($id) es para enviar un tipo de formulario pertinente
        if ($id==1 || $id==4||$id==5) {
            $html=$_short->ConstructorVista($id,null,null,null);    
        }else if($id==2 || $id==3){
            
            $html=$_short->ConstructorVista($id,$resultado[0]['ID'],null,null);
        }
    return $html;
}
add_shortcode("DDRC","PrintShortcode");


?>