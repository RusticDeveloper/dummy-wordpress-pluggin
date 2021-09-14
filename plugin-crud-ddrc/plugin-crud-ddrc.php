<?php

/**
 * Plugin Name:       DDRC-CRUD
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       plugin de un crud personalizado para worpress
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
DDRC-CRUD es un software de uso personal creado por Daniel Rivas para la implementacion 
de un crud simple dentro de wordpress
*/

//DDRC-funciones: esta linea comunica el archivo de funiones con el archivo principal del plugin
//require_once plugin_dir_path(__FILE__).'includes/ddrc_functions.php';

require_once dirname(__FILE__).'/shortcode/shorcode.class.php';

// DDRC-comentario: funcion que que se activa a la vez que el plugin
function Activar()
{
    //DDRC-comentario: Creacion de las bases de datos si aún no existen
    global $wpdb;
    $query = "CREATE TABLE IF NOT EXISTS `wp_android` (
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
        `ID` int(12) NOT NULL,
        `Accion_Crud` varchar(30) NOT NULL,
        `Shortcode` varchar(30) NOT NULL,
        PRIMARY KEY (`ID`)
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $wpdb->query($query1);
    //DDRC-comentario: Crea las carpetas que guardaran los saberes
        $ruta=dirname(__FILE__)."/../../uploads/Saberes";
        $rutaI=dirname(__FILE__)."/../../uploads/Saberes/Imagenes";
        $rutaV=dirname(__FILE__)."/../../uploads/Saberes/Videos";
        $rutaA=dirname(__FILE__)."/../../uploads/Saberes/Audios";
        $rutaT=dirname(__FILE__)."/../../uploads/Saberes/Textos";
        
    if (!file_exists($ruta)) {
        mkdir($ruta,0777,true);
    }
    if (!file_exists($rutaI)) {
        mkdir($rutaI,0777,true);
    }
    if (!file_exists($rutaV)) {
        mkdir($rutaV,0777,true);
    }
    if (!file_exists($rutaA)) {
        mkdir($rutaA,0777,true);
    }
    if (!file_exists($rutaT)) {
        mkdir($rutaT,0777,true);
    }
}
// DDRC-comentario: funcion que se activa cuando el plugin se desactiva
function Desactivar()
{
    //Reinicia todos los ajustes dentro de wordpress y los reescribe
    flush_rewrite_rules();
}
// DDRC-comentario: Registra la funcion de activacion del boton de activar de worpress
register_activation_hook(__FILE__, 'Activar');
//DDRC-comentario: Registra la funcion de desactivacion del boton de antivar de worpress
register_deactivation_hook(__FILE__, 'Desactivar');

/*DDRC-comentario: Añade un hook con la funcion nativa de wordpres de add_action */
add_action('admin_menu', 'Admin_crud');


/*DDRC-comentario: la siguiente funcion crea una pagina para añadirla al menu de administracion de worpress */
function Admin_crud()
{
    add_menu_page(
        'Gestion del CRUD', // Título pagina
        'CRUD settings', // Titulo nenú administracion
        'manage_options', // requisitos para ver el link
        //plugin_dir_url(__FILE__).'includes/adminPage.php'//this is the page path instead a slug
        'gestion_crud',  //'slug' - archivo que se muestra cuando clikamos el link
        'GestionContenido' //funcion que mostrara el contenido de la pagina de gestion

    );
}
/* DDRC-comentario: la siguiente funcion crea el contenido de la pagina de administracion del crud */
function GestionContenido()
{
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->prefix}actions;";
    $listaCruds = $wpdb->get_results($query,ARRAY_A);

    echo "<div class='wrap'>
    <h1>" . get_admin_page_title() . "</h1><br><br>
    <a id='btn_crear' class='page-title-action'>crear crud</a>
    <br><br>
    <table class='wp-list-table widefat fixes striped pages'>
        <thead>
            <th>Nombre CRUD</th>
            <th>shortcode</th>
            <th>Acciones</th>
        </thead>
        <tbody id='lista_crud'>";
    foreach ($listaCruds as $key => $value) {
        $accion = $value["Accion_Crud"];
        $shortcode = $value["Shortcode"];
        $identificacion=$value["ID"];
        echo "<tr>
           <td>$accion</td>
           <td>$shortcode</td>
           <td>
           <a href='' id= 'AC_$identificacion' class='page-title-action'>Vista Previa</a>
           <a href='' id= '' class='page-title-action'>Eliminar</a>
           </td>
           </tr>
           ";
    }
    echo '</tbody>
        </table>
        </div>

        <!-- ventana modal de creacion de archivos -->
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">vista previa accion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
      
                    <form action="" method="POST">
                        <div class="cuerpo">
                            <input id="txt_title" type="text" id="title_saber" style="width:100%" placeholder="Nombra tu saber ancestral!!"><br>
                            <textarea name="desc" id="desc_saber" cols="30" rows="10" placeholder="Describe tu saber ancestral!!"
                            style="width:100%"></textarea>
                            <br>
                                <label for="publicado">Publicar</label><br>
                            SI:<input type="radio" name="public" id="r_yes" value="no">
                            NO:<input type="radio" name="public" id="r_yes" value="si"><br>
                            <label for="NacPue">Nacionalidad o Pueblo:</label>
                            <select name="NacPue" id="NacPue">
                                <option value="Nacionalidad Achuar" selected>Nacionalidad Achuar</option>
                                <option value="Nacionalidad Andoa">Nacionalidad Andoa</option>
                                <option value="Nacionalidad Awá">Nacionalidad Awá</option>
                                <option value="Nacionalidad Chachi">Nacionalidad Chachi</option>
                                <option value="Nacionalidad Cofán">Nacionalidad Cofán</option>
                                <option value="Nacionalidad Éperara Siapidara">Nacionalidad Éperara Siapidara</option>
                                <optgroup label="Nacionalidad Kichwa">
                                <option value="Pueblo Chibuleo">Pueblo Chibuleo</option>
                                <option value="Pueblo Kañari">Pueblo Kañari</option>
                                <option value="Pueblo Karanki">Pueblo Karanki</option>
                                <option value="Pueblo Kayambi">Pueblo Kayambi</option>
                                <option value="Pueblo Amazonía">Pueblo Kichwa Amazonía</option>
                                <option value="Pueblo Kisapincha">Pueblo Kisapincha</option>
                                <option value="Pueblo Natabuela">Pueblo Natabuela</option>
                                <option value="Pueblo Otavalo">Pueblo Otavalo</option>
                                <option value="Pueblo Palta">Pueblo Palta</option>
                                <option value="Pueblo Panzaleo">Pueblo Panzaleo</option>
                                <option value="Pueblo Pasto">Pueblo Pasto</option>
                                <option value="Pueblo Puruwá">Pueblo Puruwá</option>
                                <option value="Pueblo Salasaka">Pueblo Salasaka</option>
                                <option value="Pueblo Saraguro">Pueblo Saraguro</option>
                                <option value="Pueblo Tomabela">Pueblo Tomabela</option>
                                <option value="Pueblo Waranka">Pueblo Waranka</option>
                                </optgroup>
                                <option value="Nacionalidad Sápara">Nacionalidad Sápara</option>
                                <option value="Nacionalidad Sekoya">Nacionalidad Sekoya</option>
                                <option value="Nacionalidad Shiwiar">Nacionalidad Shiwiar</option>
                                <option value="Nacionalidad Shuar">Nacionalidad Shuar</option>
                                <option value="Nacionalidad Siona">Nacionalidad Siona</option>
                                <option value="Nacionalidad Tsáchila">Nacionalidad Tsáchila</option>
                                <option value="Nacionalidad Waorani">Nacionalidad Waorani</option>
                                <option value="Pueblo Afroecuatoriano">Pueblo Afroecuatoriano</option>
                                <option value="Pueblo Huancavilca">Pueblo Huancavilca</option>
                                <option value="Pueblo Manta">Pueblo Manta</option>
                                <option value="Pueblo Montuvios">Pueblo Montuvios</option>
                            </select><br>
                            <label for="tipoArch">Tipo de archivo:</label>
                            <select name="tipoArchivo" id="tipoArch">
                                <option value="1" selected>Audio</option>
                                <option value="2">Imagen</option>
                                <option value="3">Texto</option>
                                <option value="4">Video</option>
                            </select>
                            <br>
                                Selecciona un saber <input type="file" name="saber" id="saber">
                        </div>
                    </form>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnCambiante">Crear</button>
                    </div>
            </div>
        </div>
    </div>';
}



/*DDRC-comentario: funcion que permite poner en cola la dependencia de bootstrap JS*/
function EncolarBootstrapJS($hook)
{
    echo "<script>console.log('$hook');</script>";
    if ($hook != "toplevel_page_gestion_crud") {
        return;
    }
    wp_enqueue_script('bootstrapjs', plugins_url('includes/bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', 'EncolarBootstrapJS');

/*DDRC-comentario: funcion que permite poner en cola la dependencia de bootstrap CSS*/
function EncolarBootstrapCSS($hook)
{
     //echo "<script>console.log('$hook');</script>";
     if ($hook != "toplevel_page_gestion_crud") {
        return;
    }
    wp_enqueue_style('bootstrapcss', plugins_url('includes/bootstrap/css/bootstrap.min.css', __FILE__));
}
/*DDRC-comentario: funcion que permite poner en cola un css personlizado*/
function EncolaCustomCSS($hook)
{
    add_editor_style('includes/css/vistaG.css');
    wp_enqueue_style('customCSS', plugins_url('includes/css/vistaG.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'EncolarBootstrapCSS');
add_action('admin_enqueue_scripts', 'EncolaCustomCSS');
add_action('init', 'EncolaCustomCSS');

/*DDRC-comentario: funcion que carga un js perosnalizado*/
function EncolarJS($hook)
{
    //echo "<script>console.log('$hook');</script>";
    if ($hook != "toplevel_page_gestion_crud") {
        return;
    }
    wp_enqueue_script('customjs', plugins_url('includes/js/crud_ddrc.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', 'EncolarJS');

/*DDRC-comentario: crea la interfas frontend usando la api de wordpress para crear shortcodes
shortcode
*/

function PrintShortcode($atts){
    session_start();
    
    // DDRC-comentario:instancia la clase shortcode que se creo para mostrar el front-end
        $_short = new shortCode;
    //DDRC-comentario:atributo que es estraido del shortcode como identificacion de la accion que se realizara
        $id=$atts['id'];
    //DDRC-comentario: obtencion de valores de la tabla android para compararlkos con los valores enviados desde la lista de saberes 
        global $wpdb;
        $consulta="SELECT * FROM {$wpdb->prefix}android";
        $resultado=$wpdb->get_results($consulta,ARRAY_A);
    //DDRC-comentario: pregunta si los botones de crear, editar, eliminar o cualquier otro de la lista de saberes han sido presionados para hacer la accion pertinente
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
                $respuesta=$_short->ActualizarSaber($datos,$idsaber,null,null);
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
                $idsaber=['ID'=>$alias];
                $respuesta=$_short->ActualizarSaber($datos,$idsaber,$direccion,$replazableFile);
            }
        }else if(isset($_POST['Eliminar_Saber'])) {
            $alias=$_POST['Eliminar_Saber'];
            $datos=[
                'ID'=>$alias
            ];
            $_short->EliminarSaber($datos);
            
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

    //DDRC-comentario: pregunta que acciona con el id($id) es para enviar un tipo de formulario pertinente
        if ($id==1 || $id==4||$id==5) {
            $html=$_short->ConstructorVista($id,null,null,null);    
        }else if($id==2 || $id==3){
            
            $html=$_short->ConstructorVista($id,$resultado[0]['ID'],null,null);
        }
    return $html;
}
add_shortcode("DDRC","PrintShortcode");
?>


