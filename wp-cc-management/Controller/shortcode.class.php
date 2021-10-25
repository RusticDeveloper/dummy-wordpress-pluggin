<?php
//DDRC-C: clase que crea el front-end de nuestro crud
class shortCode
{


    //DDRC-C:funcion que obtiene los datos de la tabla de android 
    public function ObtenerDatos()
    {
        global $wpdb;
        $tabla = "{$wpdb->prefix}android";
        $query = "SELECT * FROM $tabla";
        $rawData = $wpdb->get_results($query, ARRAY_A);
        if (empty($rawData)) {
            $rawData = array();
        }
        return $rawData;
    }
    //DDRC-C:funcion que obtiene los datos por nacionalidad y tipo de archivo de la tabla de android 
    public function ObtenerDatosPor($NPD, $TPA)
    {
        global $wpdb;
        $tabla = "{$wpdb->prefix}android";
        $query = "SELECT * FROM $tabla WHERE `NacionalidadoPueblo`='$NPD' && `TipoArchivo`='$TPA'";
        $rawData = $wpdb->get_results($query, ARRAY_A);
        if (empty($rawData)) {
            $rawData = "not-found";
        }
        return $rawData;
    }
    //DDRC-C:funcion que obtiene los datos por id de la tabla de android 
    public function ObtenerDatosEspecificos($id)
    {
        global $wpdb;
        $tabla = "{$wpdb->prefix}android";
        $query = "SELECT * FROM `$tabla` WHERE `ID`=$id";
        $rawData = $wpdb->get_results($query, ARRAY_A);
        if (empty($rawData)) {
            $rawData = array();
        }
        return $rawData[0];
    }
    //DDRC-C: dependiendo de la accion se creara un formulario o un encabezado para imprimir en pantalla
    public function FormInit($action)
    {
        if ($action == 1) {
            $html = "<div class='wrap'>
            <h1>Crear Saber</h1><hr>
        <form action='' enctype='multipart/form-data' method='post'>";
        } else if ($action == 2) {
            $html = "<div class='wrap'>
            <h1>Actualizar Saber</h1><hr>
        <form action='' enctype='multipart/form-data' method='post'>";
        } else if ($action == 3) {
            $html = "<div class='wrap'>
            <h1>Eliminar Saber</h1><hr>
        <form action='' enctype='multipart/form-data' method='post'>";
        } else if ($action == 4) {
            $html = "
            <!-- para la paginacion de la tabla -->
            
            <div class='wrap'>
            <h1>Lista de saberes</h1><br>
            <form action='' enctype='multipart/form-data' method='post'>
            <button class='btn btn-danger' id='share_saber' name='share_saber'>Compartir Saber</button><br><br><hr>
            </form>
            <table class='wp-list-table widefat fixes striped pages' id='tblst'>
        <thead>
            <th>ID</th>
            <th>Titulo</th>
            <th>Nación-Pueblo</th>
            <th>Multimedia</th>
            <th>Acciones</th>
        </thead>
        <tbody id='lista_crud'>";
        }

        return $html;
    }
    //DDRC-C: funcion de cierre del formulario que muestra y recopila informacion de la base de datos
    public function FormEnd($action, $id)
    {
        if ($action == 1) {
            $alias = "Crear Saber";
            $html = "<br>
        <button class='animacion' value='$id' name='$alias' id='$alias'>$alias
        <svg>
        <rect x='0' y='0' fill='none' />
        </svg>
        </button>
        <a href='' onclick='cancel()' class='btn btn-danger'>Cancelar</a>
        </form>
        </div>
        <script>

        function cancel(){
            $_POST[cancelar];
        }
        </script>";
        } else if ($action == 2) {
            $alias = "Actualizar Saber";
            $html = "<br>
        <button class='animacion' value='$id' name='$alias' id='$alias'>$alias
        <svg>
        <rect x='0' y='0' fill='none' />
        </svg>
        </button>
        <a href='' onclick='cancel()' class='btn btn-danger'>Cancelar</a>
        </form>
        </div>
        <script>

        function cancel(){
            $_POST[cancelar];
        }
        </script>";
        } else if ($action == 3) {
            $alias = "Eliminar Saber";
            $html = "<br>
        <button class='animacion' value='$id' name='$alias' id='$alias'>$alias
        <svg>
        <rect x='0' y='0' fill='none' />
        </svg>
        </button>
        <a href='' onclick='cancel()' class='btn btn-danger'>Cancelar</a>
        </form>
        </div>
        <script>

        function cancel(){
            $_POST[cancelar];
        }
        </script>";
        } else if ($action == 4) {
            $html = '</tbody>
            </table>
            </div>
            ';
        }

        return $html;
    }

    //DDRC-C: cuerpo del formulario donde se recopilan y muestran los datos 
    public function FormContent($action, $id, $title, $description, $TipoA, $NacPU, $publico, $filePath, $TopicTag)
    {
        if ($action == 3) {
            $yesno = ($publico == 'True') ? "SI:<input disabled type='radio' name='public' id='r_yes' value='True' checked>
              NO:<input disabled type='radio' name='public' id='r_no' value='False'><br>"
                : "SI:<input disabled type='radio' name='public' id='r_yes' value='True'>
               NO:<input disabled type='radio' name='public' id='r_no' value='False' checked><br>";
            $nacPueVal = "<option value='$NacPU' selected>$NacPU</option>";
            $typeVal = "<option value='$TipoA' selected>$TipoA</option>";
            $RealPath = explode('../', $filePath);
            $RealPath1 = "/wordpress/wp-content/" . $RealPath[sizeof($RealPath) - 1];
            if ($TipoA == 'Audio') {
                $sourceFile = "<audio src='$RealPath1'  controls></audio><br>";
                $FType = "<input type='file' name='saber' required accept='audio/mpeg' id='saber' >"; //not used maybe in the future
            } else if ($TipoA == 'Video') {
                $sourceFile = "<video src='$RealPath1' controls width='300' heigth='300'></video><br>";
                $FType = "<input type='file' name='saber' required accept='video/mp4' id='saber' >"; //not used maybe in the future
            } else if ($TipoA == 'Imagen') {
                $sourceFile = "<img src='$RealPath1' width='300' heigth='300' alt='imagen del saber'><br>";
                $FType = "<input type='file' name='saber' required accept='image/jpeg' id='saber' >"; //not used maybe in the future
            } else if ($TipoA == 'Texto') {
                $sourceFile = "<iFrame src='$RealPath1' width='500' heigth='900'></iFrame> <br>";
                $FType = "<input type='file' name='saber'required accept='application/pdf' id='saber' >"; //not used maybe in the future
            }
            $html = "<div class='cuerpo' id='$id'>
                <input disabled type='text' id='title_saber' name='title_saber' value='$title' required style='width:100%' placeholder='Nombra tu saber ancestral!!'><br>
                <input disabled type='text' id='Temas_saber' name='Temas_saber' value='$TopicTag' required style='width:100%' placeholder='Introduce la/las tematicas del saber'><br>
                <input disabled type='hidden' id='rutaSaber' name='rutaSaber' value='$filePath' ><br>
                <textarea disabled name='desc_saber' id='desc_saber' cols='30' rows='10' required placeholder='Describe tu saber ancestral!!'
                style='width:100%'>$description</textarea>
                <br>
                <label for='publicado'>Publicar</label><br>" . $yesno . "               
                <label for='NacPue'>Nacionalidad o Pueblo:</label>
                <select disabled name='NacPue' id='NacPue'>" . $nacPueVal . "
                    <option value='Nacionalidad Achuar'>Nacionalidad Achuar</option>
                    <option value='Nacionalidad Andoa'>Nacionalidad Andoa</option>
                    <option value='Nacionalidad Awá'>Nacionalidad Awá</option>
                    <option value='Nacionalidad Chachi'>Nacionalidad Chachi</option>
                    <option value='Nacionalidad Cofán'>Nacionalidad Cofán</option>
                    <option value='Nacionalidad Éperara Siapidara'>Nacionalidad Éperara Siapidara</option>
                    <option value='Nacionalidad Sápara'>Nacionalidad Sápara</option>
                    <option value='Nacionalidad Sekoya'>Nacionalidad Sekoya</option>
                    <option value='Nacionalidad Shiwiar'>Nacionalidad Shiwiar</option>
                    <option value='Nacionalidad Shuar'>Nacionalidad Shuar</option>
                    <option value='Nacionalidad Siona'>Nacionalidad Siona</option>
                    <option value='Nacionalidad Tsáchila'>Nacionalidad Tsáchila</option>
                    <option value='Nacionalidad Waorani'>Nacionalidad Waorani</option>
                    <optgroup label='Nacionalidad Kichwa'>
                    <option value='Pueblo Chibuleo'>Pueblo Chibuleo</option>
                    <option value='Pueblo Kañari'>Pueblo Kañari</option>
                    <option value='Pueblo Karanki'>Pueblo Karanki</option>
                    <option value='Pueblo Kayambi'>Pueblo Kayambi</option>
                    <option value='Pueblo Amazonía'>Pueblo Kichwa Amazonía</option>
                    <option value='Pueblo Kisapincha'>Pueblo Kisapincha</option>
                    <option value='Pueblo Natabuela'>Pueblo Natabuela</option>
                    <option value='Pueblo Otavalo'>Pueblo Otavalo</option>
                    <option value='Pueblo Palta'>Pueblo Palta</option>
                    <option value='Pueblo Panzaleo'>Pueblo Panzaleo</option>
                    <option value='Pueblo Pasto'>Pueblo Pasto</option>
                    <option value='Pueblo Puruwá'>Pueblo Puruwá</option>
                    <option value='Pueblo Salasaka'>Pueblo Salasaka</option>
                    <option value='Pueblo Saraguro'>Pueblo Saraguro</option>
                    <option value='Pueblo Tomabela'>Pueblo Tomabela</option>
                    <option value='Pueblo Waranka'>Pueblo Waranka</option>
                    </optgroup>
                    <option value='Pueblo Afroecuatoriano'>Pueblo Afroecuatoriano</option>
                    <option value='Pueblo Huancavilca'>Pueblo Huancavilca</option>
                    <option value='Pueblo Manta'>Pueblo Manta</option>
                    <option value='Pueblo Montuvios'>Pueblo Montuvios</option>
                </select><br>
                <label for='tipoArch'>Tipo de archivo:</label>
                <select disabled name='tipoArchivo' id='tipoArch'>" . $typeVal . "
                    <option value='Audio'>Audio</option>
                    <option value='Imagen'>Imagen</option>
                    <option value='Texto'>Texto</option>
                    <option value='Video'>Video</option>
                </select>
                <br>
                " . $sourceFile . "
            </div>";
        }else if ($action != 1 && $action != 3) {
            $yesno = ($publico == 'True') ? "SI:<input type='radio' name='public' id='r_yes' value='True' checked>
              NO:<input type='radio' name='public' id='r_no' value='False'><br>"
                : "SI:<input type='radio' name='public' id='r_yes' value='True'>
               NO:<input type='radio' name='public' id='r_no' value='False' checked><br>";
            $nacPueVal = "<option value='$NacPU' selected>$NacPU</option>";
            $typeVal = "<option value='$TipoA' selected>$TipoA</option>";
            $RealPath = explode('../', $filePath);
            $RealPath1 = "/wordpress/wp-content/" . $RealPath[sizeof($RealPath) - 1];
            if ($TipoA == 'Audio') {
                $sourceFile = "<audio src='$RealPath1'  controls></audio><br>";
                $FType = "<input type='file' name='saber' required accept='audio/mpeg' id='saber' >"; //not used maybe in the future
            } else if ($TipoA == 'Video') {
                $sourceFile = "<video src='$RealPath1' controls width='300' heigth='300'></video><br>";
                $FType = "<input type='file' name='saber' required accept='video/mp4' id='saber' >"; //not used maybe in the future
            } else if ($TipoA == 'Imagen') {
                $sourceFile = "<img src='$RealPath1' width='300' heigth='300' alt='imagen del saber'><br>";
                $FType = "<input type='file' name='saber' required accept='image/jpeg' id='saber' >"; //not used maybe in the future
            } else if ($TipoA == 'Texto') {
                $sourceFile = "<iFrame src='$RealPath1' width='500' heigth='900'></iFrame> <br>";
                $FType = "<input type='file' name='saber'required accept='application/pdf' id='saber' >"; //not used maybe in the future
            }
            $html = "<div class='cuerpo' id='$id'>
                <input type='text' id='title_saber' name='title_saber' value='$title' required style='width:100%' placeholder='Nombra tu saber ancestral!!'><br>
                <input type='text' id='Temas_saber' name='Temas_saber' value='$TopicTag' required style='width:100%' placeholder='Introduce la/las tematicas del saber'><br>
                <input type='hidden' id='rutaSaber' name='rutaSaber' value='$filePath' ><br>
                <textarea name='desc_saber' id='desc_saber' cols='30' rows='10' required placeholder='Describe tu saber ancestral!!'
                style='width:100%'>$description</textarea>
                <br>
                <label for='publicado'>Publicar</label><br>" . $yesno . "               
                <label for='NacPue'>Nacionalidad o Pueblo:</label>
                <select name='NacPue' id='NacPue'>" . $nacPueVal . "
                    <option value='Nacionalidad Achuar'>Nacionalidad Achuar</option>
                    <option value='Nacionalidad Andoa'>Nacionalidad Andoa</option>
                    <option value='Nacionalidad Awá'>Nacionalidad Awá</option>
                    <option value='Nacionalidad Chachi'>Nacionalidad Chachi</option>
                    <option value='Nacionalidad Cofán'>Nacionalidad Cofán</option>
                    <option value='Nacionalidad Éperara Siapidara'>Nacionalidad Éperara Siapidara</option>
                    <option value='Nacionalidad Sápara'>Nacionalidad Sápara</option>
                    <option value='Nacionalidad Sekoya'>Nacionalidad Sekoya</option>
                    <option value='Nacionalidad Shiwiar'>Nacionalidad Shiwiar</option>
                    <option value='Nacionalidad Shuar'>Nacionalidad Shuar</option>
                    <option value='Nacionalidad Siona'>Nacionalidad Siona</option>
                    <option value='Nacionalidad Tsáchila'>Nacionalidad Tsáchila</option>
                    <option value='Nacionalidad Waorani'>Nacionalidad Waorani</option>
                    <optgroup label='Nacionalidad Kichwa'>
                    <option value='Pueblo Chibuleo'>Pueblo Chibuleo</option>
                    <option value='Pueblo Kañari'>Pueblo Kañari</option>
                    <option value='Pueblo Karanki'>Pueblo Karanki</option>
                    <option value='Pueblo Kayambi'>Pueblo Kayambi</option>
                    <option value='Pueblo Amazonía'>Pueblo Kichwa Amazonía</option>
                    <option value='Pueblo Kisapincha'>Pueblo Kisapincha</option>
                    <option value='Pueblo Natabuela'>Pueblo Natabuela</option>
                    <option value='Pueblo Otavalo'>Pueblo Otavalo</option>
                    <option value='Pueblo Palta'>Pueblo Palta</option>
                    <option value='Pueblo Panzaleo'>Pueblo Panzaleo</option>
                    <option value='Pueblo Pasto'>Pueblo Pasto</option>
                    <option value='Pueblo Puruwá'>Pueblo Puruwá</option>
                    <option value='Pueblo Salasaka'>Pueblo Salasaka</option>
                    <option value='Pueblo Saraguro'>Pueblo Saraguro</option>
                    <option value='Pueblo Tomabela'>Pueblo Tomabela</option>
                    <option value='Pueblo Waranka'>Pueblo Waranka</option>
                    </optgroup>
                    <option value='Pueblo Afroecuatoriano'>Pueblo Afroecuatoriano</option>
                    <option value='Pueblo Huancavilca'>Pueblo Huancavilca</option>
                    <option value='Pueblo Manta'>Pueblo Manta</option>
                    <option value='Pueblo Montuvios'>Pueblo Montuvios</option>
                </select><br>
                <label for='tipoArch'>Tipo de archivo:</label>
                <select name='tipoArchivo' id='tipoArch'>" . $typeVal . "
                    <option value='Audio'>Audio</option>
                    <option value='Imagen'>Imagen</option>
                    <option value='Texto'>Texto</option>
                    <option value='Video'>Video</option>
                </select>
                <br>
                " . $sourceFile . "
                <b>Ingrese un saber</b><input type='file' name='saber' id='saber' ><br>
            </div>";
        } else {
            $html = "<div class='cuerpo'>
            <input id='txt_title' type='text' id='title_saber' name='title_saber' style='width:100%' placeholder='Nombra tu saber ancestral!!'><br>
            <input type='text' id='Temas_saber' name='Temas_saber' required style='width:100%' placeholder='Introduce la/las tematicas del saber'><br>
            <textarea name='desc_saber' id='desc_saber' cols='30' rows='10' placeholder='Describe tu saber ancestral!!'
            style='width:100%'></textarea>
            <br>
            <label for='publicado'>Publicar</label><br>
            SI:<input type='radio' name='public' id='r_yes' value='True'>
            NO:<input type='radio' name='public' id='r_no' value='False'><br>
            <label for='NacPue'>Nacionalidad o Pueblo:</label>
            <select name='NacPue' id='NacPue'>
                <option value='Nacionalidad Achuar' selected>Nacionalidad Achuar</option>
                <option value='Nacionalidad Andoa'>Nacionalidad Andoa</option>
                <option value='Nacionalidad Awá'>Nacionalidad Awá</option>
                <option value='Nacionalidad Chachi'>Nacionalidad Chachi</option>
                <option value='Nacionalidad Cofán'>Nacionalidad Cofán</option>
                <option value='Nacionalidad Éperara Siapidara'>Nacionalidad Éperara Siapidara</option>
                <option value='Nacionalidad Sápara'>Nacionalidad Sápara</option>
                <option value='Nacionalidad Sekoya'>Nacionalidad Sekoya</option>
                <option value='Nacionalidad Shiwiar'>Nacionalidad Shiwiar</option>
                <option value='Nacionalidad Shuar'>Nacionalidad Shuar</option>
                <option value='Nacionalidad Siona'>Nacionalidad Siona</option>
                <option value='Nacionalidad Tsáchila'>Nacionalidad Tsáchila</option>
                <option value='Nacionalidad Waorani'>Nacionalidad Waorani</option>
                <optgroup label='Nacionalidad Kichwa'>
                <option value='Pueblo Chibuleo'>Pueblo Chibuleo</option>
                <option value='Pueblo Kañari'>Pueblo Kañari</option>
                <option value='Pueblo Karanki'>Pueblo Karanki</option>
                <option value='Pueblo Kayambi'>Pueblo Kayambi</option>
                <option value='Pueblo Amazonía'>Pueblo Kichwa Amazonía</option>
                <option value='Pueblo Kisapincha'>Pueblo Kisapincha</option>
                <option value='Pueblo Natabuela'>Pueblo Natabuela</option>
                <option value='Pueblo Otavalo'>Pueblo Otavalo</option>
                <option value='Pueblo Palta'>Pueblo Palta</option>
                <option value='Pueblo Panzaleo'>Pueblo Panzaleo</option>
                <option value='Pueblo Pasto'>Pueblo Pasto</option>
                <option value='Pueblo Puruwá'>Pueblo Puruwá</option>
                <option value='Pueblo Salasaka'>Pueblo Salasaka</option>
                <option value='Pueblo Saraguro'>Pueblo Saraguro</option>
                <option value='Pueblo Tomabela'>Pueblo Tomabela</option>
                <option value='Pueblo Waranka'>Pueblo Waranka</option>
                </optgroup>
                <option value='Pueblo Afroecuatoriano'>Pueblo Afroecuatoriano</option>
                <option value='Pueblo Huancavilca'>Pueblo Huancavilca</option>
                <option value='Pueblo Manta'>Pueblo Manta</option>
                <option value='Pueblo Montuvios'>Pueblo Montuvios</option>
            </select><br>
            <label for='tipoArch'>Tipo de archivo:</label>
            <select name='tipoArchivo' id='tipoArch'>
                <option value='Audio' selected>Audio</option>
                <option value='Imagen'>Imagen</option>
                <option value='Texto'>Texto</option>
                <option value='Video'>Video</option>
            </select>
            <br>
                <b>Ingrese un saber</b><input type='file' name='saber'required id='saber' >
        </div>";
        }
        return $html;
    }
    //DDRC-C: funcion que muestra los datos en bulto listos para ser eliminados o modificados
    public function FormContentList($action, $id, $title, $TipoA, $NacPU)
    {
        if ($action == 4) {
            $html = "<tr>
               <td>$id</td>
               <td>$title</td>
               <td>$NacPU</td>
               <td>$TipoA</td>
               <td>
               <form action='' method='post'>
               <button id='UP_$id' name='UP_$id' value='$id'class='btn btn-success'>Actualizar</button>
               <button id='DEL_$id' name='DEL_$id' value='$id' class='btn btn-warning'>Eliminar</button>
               </form>
               </td>
               </tr>
               ";
        }

        return $html;
    }
    //DDRC-C: construye la vista fron-end uniendo todos los datos del formulario forminput,-output,-content
    public function ConstructorVista($action, $identifier, $NAPU, $TIPA)
    {
        $datos = $this->ObtenerDatos();
        $datosEspecificos = $this->ObtenerDatosEspecificos($identifier);
        $fI = "";
        $fE = "";
        $FB = "";
        $html = "";
        if ($action == 1) { //accion Crear [create] id=1
            $fI = $this->FormInit(1);
            $FB = $this->FormContent(1, null, null, null, null, null, null, null, null);
            $fE = $this->FormEnd(1, null);
        } else if ($action == 2) { //accion Actualizar [update] id=2
            $alias = $datosEspecificos['ID'];
            $header = $datosEspecificos['Titulo'];
            $abstract = $datosEspecificos['Descripcion'];
            $kindof = $datosEspecificos['TipoArchivo'];
            $belongTo = $datosEspecificos['NacionalidadoPueblo'];
            $pub = $datosEspecificos['Publicado'];
            $rutaFile = $datosEspecificos['RutaSaber'];
            $Topic = $datosEspecificos['TagsTematicas'];
            $fI = $this->FormInit(2);
            $FB = $this->FormContent(2, $alias, $header, $abstract, $kindof, $belongTo, $pub, $rutaFile, $Topic);
            $fE = $this->FormEnd(2, $identifier);
        } else if ($action == 3) { //accion delete [delete] id=3
            $alias = $datosEspecificos['ID'];
            $header = $datosEspecificos['Titulo'];
            $abstract = $datosEspecificos['Descripcion'];
            $kindof = $datosEspecificos['TipoArchivo'];
            $belongTo = $datosEspecificos['NacionalidadoPueblo'];
            $pub = $datosEspecificos['Publicado'];
            $rutaFile = $datosEspecificos['RutaSaber'];
            $Topic = $datosEspecificos['TagsTematicas'];
            $fI = $this->FormInit(3);
            $FB = $this->FormContent(3, $alias, $header, $abstract, $kindof, $belongTo, $pub, $rutaFile, $Topic);
            $fE = $this->FormEnd(3, $identifier);
        } else if ($action == 4) { //accion Listar [listar] id=4
            $fI = $this->FormInit(4);
            foreach ($datos as $key => $value) {
                $FB .= $this->FormContentList(4, $value['ID'], $value['Titulo'], $value['TipoArchivo'], $value['NacionalidadoPueblo']);
            }
            $fE = $this->FormEnd(4, null);
        } else if ($action == 5) { // muestra en el repositorio los pueblos y naciones 
            $fI = $this->NacPueGeneral();
        } else if ($action == 6) {//muestra los tipos de saberes que tiene el repositorio de corape
            $fI = $this->NacPueTipos();
        } else if ($action == 7) {//muestra los saberes de filtrados por nacion y pueblo
            $fI = $this->NacPueSpesificSet($NAPU, $TIPA);
        } else if ($action == 8) {//muestra un saber especifico en repositorio
            $fI = $this->NacPueEspecifico($NAPU, $TIPA, $identifier);
        }
        $html .= $fI;
        $html .= $FB;
        $html .= $fE;
        return $html;
    }
    //DDRC-C: funcion para crear saberes que trabaja directamente con el shortcode para el front-end
    public function GuardarSaber($informacion, $dir)
    {
        global $wpdb;
        require_once(ABSPATH . '/wp-load.php');
        $rutaI = dirname(__FILE__) . "/../../../uploads/Saberes/Imagenes/";
        $rutaV = dirname(__FILE__) . "/../../../uploads/Saberes/Videos/";
        $rutaA = dirname(__FILE__) . "/../../../uploads/Saberes/Audios/";
        $rutaT = dirname(__FILE__) . "/../../../uploads/Saberes/Textos/";
        $rutaI1 = "http://localhost/wordpress/wp-content/uploads/Saberes/Imagenes/";
        $rutaV1 = "http://localhost/wordpress/wp-content/uploads/Saberes/Videos/";
        $rutaA1 = "http://localhost/wordpress/wp-content/uploads/Saberes/Audios/";
        $rutaT1 = "http://localhost/wordpress/wp-content/uploads/Saberes/Textos/";
        $date = date('Y/m/d h:i:s', time()); //the time() is by default taken and is an optional atribute that refers to the integer unix timestamp
        $infoWordpress = [
            'post_author' => '1',
            'post_date' => $date,
            'post_date_gmt' => $date,
            'post_content' => $informacion['Descripcion'],
            'post_title' => $informacion['Titulo'],
            'post_status' => 'inherit',
            'comment_status' => 'open',
            'ping_status' => 'closed',
            'post_name' => $informacion['Titulo'],
            'post_modified' => $date,
            'post_modified_gmt' => $date,
            'post_parent' => '0',
            'menu_order' => '0',
            'post_type' => 'attachment',
            'comment_count' => '0',
        ];
        if ($informacion['TipoArchivo'] == 'Audio') {
            move_uploaded_file($dir, $rutaA . $informacion['NombreSaber']);
            $informacion['RutaSaber'] = $rutaA . $informacion['NombreSaber'];
            $infoWordpress['guid'] = $rutaA1 . $informacion['NombreSaber'];
            $infoWordpress['post_mime_type'] = 'audio/mpeg';
            $idPost = wp_insert_post($infoWordpress);
            // $idSaber=wp_insert_attachment($infoWorpress);
            if (!is_wp_error($idPost)) {
                require_once(ABSPATH . '/wp-admin/includes/image.php');
                require_once(ABSPATH . '/wp-admin/includes/media.php');
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Audios/' . $informacion['NombreSaber']);
                wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                set_post_thumbnail( $idPost, $idPost );
            }
        } else if ($informacion['TipoArchivo'] == 'Imagen') {
            move_uploaded_file($dir, $rutaI . $informacion['NombreSaber']);
            $informacion['RutaSaber'] = $rutaI . $informacion['NombreSaber'];
            $infoWordpress['guid'] = $rutaI1 . $informacion['NombreSaber'];
            $infoWordpress['post_mime_type'] = 'image/jpeg';
            $idPost = wp_insert_post($infoWordpress);
            // $idSaber=wp_insert_attachment($infoWorpress);
            if (!is_wp_error($idPost)) {
                require_once(ABSPATH . '/wp-admin/includes/image.php');
                require_once(ABSPATH . '/wp-admin/includes/media.php');
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                // ABSPATH.'/wp-content/uploads/Saberes/Imagenes/'.$informacion['NombreSaber'])
                // $infoWordpress['ID']=$idPost;
                $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Imagenes/' . $informacion['NombreSaber']);
                wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                set_post_thumbnail( $idPost, $idPost );
            }
        } else if ($informacion['TipoArchivo'] == 'Video') {
            move_uploaded_file($dir, $rutaV . $informacion['NombreSaber']);
            $informacion['RutaSaber'] = $rutaV . $informacion['NombreSaber'];
            $infoWordpress['guid'] = $rutaV1 . $informacion['NombreSaber'];
            $infoWordpress['post_mime_type'] = 'video/mp4';
            $idPost = wp_insert_post($infoWordpress);
            // $idSaber=wp_insert_attachment($infoWorpress);
            if (!is_wp_error($idPost)) {
                require_once(ABSPATH . '/wp-admin/includes/image.php');
                require_once(ABSPATH . '/wp-admin/includes/media.php');
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Videos/' . $informacion['NombreSaber']);
                wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                set_post_thumbnail( $idPost, $idPost );
            }
        } else if ($informacion['TipoArchivo'] == 'Texto') {
            move_uploaded_file($dir, $rutaT . $informacion['NombreSaber']);
            $informacion['RutaSaber'] = $rutaT . $informacion['NombreSaber'];
            $infoWordpress['guid'] = $rutaT1 . $informacion['NombreSaber'];
            $infoWordpress['post_mime_type'] = 'aplication/pdf';
            $idPost = wp_insert_post($infoWordpress);
            // $idSaber=wp_insert_attachment($infoWorpress,'Saberes/Textos/'.$informacion['NombreSaber'],$idPost);
            if (!is_wp_error($idPost)) {
                require_once(ABSPATH . '/wp-admin/includes/image.php');
                require_once(ABSPATH . '/wp-admin/includes/media.php');
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Textos/' . $informacion['NombreSaber']);
                wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                set_post_thumbnail( $idPost, $idPost );
            }
        }

        $tabla = "{$wpdb->prefix}android";
        return $wpdb->insert($tabla, $informacion);
    }
    //DDRC-C: funcion para actualizar saberes que trabaja directamente con el shortcode para el front-end
    public function ActualizarSaber($informacion, $donde, $dir, $replaceFile, $infoWordpress)
    {
        global $wpdb;
        require_once(ABSPATH . '/wp-load.php');
        $date = date('Y/m/d h:i:s', time()); //the time() is by default taken and is an optional atribute that refers to the integer unix timestamp
        $rutaI = dirname(__FILE__) . "/../../../uploads/Saberes/Imagenes/";
        $rutaV = dirname(__FILE__) . "/../../../uploads/Saberes/Videos/";
        $rutaA = dirname(__FILE__) . "/../../../uploads/Saberes/Audios/";
        $rutaT = dirname(__FILE__) . "/../../../uploads/Saberes/Textos/";
        $infoWordpress = [
            'post_author' => '1',
            'post_date' => $date,
            'post_date_gmt' => $date,
            'post_content' => $informacion['Descripcion'],
            'post_title' => $informacion['Titulo'],
            'post_status' => 'inherit',
            'comment_status' => 'open',
            'ping_status' => 'closed',
            'post_name' => $informacion['Titulo'],
            'post_modified' => $date,
            'post_modified_gmt' => $date,
            'post_parent' => '0',
            'menu_order' => '0',
            'post_type' => 'attachment',
            'comment_count' => '0',
        ];
        if (!is_null($dir)) {
            if ($informacion['TipoArchivo'] == 'Audio') {
                $postupload = get_posts(
                    array(
                        'title' => $informacion['titulo'],
                        'numberposts'      => 1,
                        'order'            => 'DESC',
                        'fields' => 'ids'
                    )

                );
                $infoWordpress['ID'] = $postupload["ID"];
                move_uploaded_file($dir, $rutaA . $informacion['NombreSaber']);
                $informacion['RutaSaber'] = $rutaA . $informacion['NombreSaber'];
                unlink($replaceFile);
                $infoWordpress['guid'] = $rutaA . $informacion['NombreSaber'];
                $infoWordpress['post_mime_type'] = 'audio/mpeg';
                $idPost = wp_insert_post($infoWordpress);
                // $idSaber=wp_insert_attachment($infoWorpress,'Saberes/Textos/'.$informacion['NombreSaber'],$idPost);
                if (!is_wp_error($idPost)) {
                    require_once(ABSPATH . '/wp-admin/includes/image.php');
                    require_once(ABSPATH . '/wp-admin/includes/media.php');
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Audios/' . $informacion['NombreSaber']);
                    wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                }
            } else if ($informacion['TipoArchivo'] == 'Imagen') {
                $postupload = get_posts(
                    array('title' => $informacion['titulo'])
                );
                $infoWordpress['ID'] = $postupload['ID'];
                move_uploaded_file($dir, $rutaI . $informacion['NombreSaber']);
                $informacion['RutaSaber'] = $rutaI . $informacion['NombreSaber'];
                unlink($replaceFile);
                $infoWordpress['guid'] = $rutaI . $informacion['NombreSaber'];
                $infoWordpress['post_mime_type'] = 'image/jpeg';
                $idPost = wp_insert_post($infoWordpress);
                // $idSaber=wp_insert_attachment($infoWorpress,'Saberes/Textos/'.$informacion['NombreSaber'],$idPost);
                if (!is_wp_error($idPost)) {
                    require_once(ABSPATH . '/wp-admin/includes/image.php');
                    require_once(ABSPATH . '/wp-admin/includes/media.php');
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Imagenes/' . $informacion['NombreSaber']);
                    wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                }
            } else if ($informacion['TipoArchivo'] == 'Video') {
                $postupload = get_posts(
                    array('title' => $informacion['titulo'])
                );
                $infoWordpress['ID'] = $postupload['ID'];
                move_uploaded_file($dir, $rutaV . $informacion['NombreSaber']);
                $informacion['RutaSaber'] = $rutaV . $informacion['NombreSaber'];
                unlink($replaceFile);
                $infoWordpress['guid'] = $rutaV . $informacion['NombreSaber'];
                $infoWordpress['post_mime_type'] = 'video/mp4';
                $idPost = wp_insert_post($infoWordpress);
                // $idSaber=wp_insert_attachment($infoWorpress,'Saberes/Textos/'.$informacion['NombreSaber'],$idPost);
                if (!is_wp_error($idPost)) {
                    require_once(ABSPATH . '/wp-admin/includes/image.php');
                    require_once(ABSPATH . '/wp-admin/includes/media.php');
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Videos/' . $informacion['NombreSaber']);
                    wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                }
            } else if ($informacion['TipoArchivo'] == 'Texto') {
                $postupload = get_posts(
                    array('title' => $informacion['titulo'])
                );
                $infoWordpress['ID'] = $postupload['ID'];
                move_uploaded_file($dir, $rutaT . $informacion['NombreSaber']);
                $informacion['RutaSaber'] = $rutaT . $informacion['NombreSaber'];
                unlink($replaceFile);
                $infoWordpress['guid'] = $rutaT . $informacion['NombreSaber'];
                $infoWordpress['post_mime_type'] = 'aplication/pdf';
                $idPost = wp_insert_post($infoWordpress);
                // $idSaber=wp_insert_attachment($infoWorpress,'Saberes/Textos/'.$informacion['NombreSaber'],$idPost);
                if (!is_wp_error($idPost)) {
                    require_once(ABSPATH . '/wp-admin/includes/image.php');
                    require_once(ABSPATH . '/wp-admin/includes/media.php');
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    $respuesta_meta_saber = wp_generate_attachment_metadata($idPost, ABSPATH . 'wp-content/uploads/Saberes/Textos/' . $informacion['NombreSaber']);
                    wp_update_attachment_metadata($idPost, $respuesta_meta_saber);
                }
            }
        }

        $tabla = "{$wpdb->prefix}android";
        return $wpdb->update($tabla, $informacion, $donde);
    }
    //DDRC-C: funcion para eliminar saberes que trabaja directamente con el shortcode para el front-end
    public function EliminarSaber($donde, $donde1)
    {
        global $wpdb;
        require_once(ABSPATH . '/wp-load.php');
        $postupload = get_posts(
            array(
                'title' => $donde1,
                'numberposts'      => 1,
                'order'            => 'DESC',
               
            )
        );
        $idvalor = $postupload["ID"];
        // $tablaWordpress = "{$wpdb->prefix}posts";
        /* TODO: hacer que se elimine tambien de la base de datos de wp_post*/
        // $wpdb->delete($tablaWordpress, $donde);
        wp_delete_post($idvalor);
        $tabla = "{$wpdb->prefix}android";
        return $wpdb->delete($tabla, $donde);
    }

    //DDRC-C: funcion para mostrar naciones y pueblos que trabaja directamente con el shortcode para el front-end
    public function NacPueGeneral()
    {
        $datos = [
            'Nacionalidad Achuar.jpg', 'Nacionalidad Andoa.jpg', 'Nacionalidad Awá.jpg', 'Nacionalidad Chachi.jpg', 'Nacionalidad Cofán.jpg', 'Nacionalidad Éperara Siapidara.jpg',
            'Nacionalidad Sápara.jpg', 'Nacionalidad Sekoya.jpg', 'Nacionalidad Shiwiar.jpg', 'Nacionalidad Shuar.jpg', 'Nacionalidad Siona.jpg', 'Nacionalidad Tsáchila.jpg',
            'Nacionalidad Waorani.jpg', 'Pueblo Chibuleo.jpg', 'Pueblo Kañari.jpg', 'Pueblo Karanki.jpg', 'Pueblo Kayambi.jpg', 'Pueblo Amazonía.jpg', 'Pueblo Kisapincha.jpg',
            'Pueblo Natabuela.jpg', 'Pueblo Otavalo.jpg', 'Pueblo Palta.jpg', 'Pueblo Panzaleo.jpg', 'Pueblo Pasto.jpg', 'Pueblo Puruwá.jpg', 'Pueblo Salasaka.jpg', 'Pueblo Saraguro.jpg',
            'Pueblo Tomabela.jpg', 'Pueblo Waranka.jpg', 'Pueblo Afroecuatoriano.jpg', 'Pueblo Huancavilca.jpg', 'Pueblo Manta.jpg', 'Pueblo Montuvios.jpg'
        ];
        $nombres = [
            'Nacionalidad Achuar', 'Nacionalidad Andoa', 'Nacionalidad Awá', 'Nacionalidad Chachi', 'Nacionalidad Cofán', 'Nacionalidad Épera',
            'Nacionalidad Sápara', 'Nacionalidad Sekoya', 'Nacionalidad Shiwiar', 'Nacionalidad Shuar', 'Nacionalidad Siona', 'Nacionalidad Tsáchila',
            'Nacionalidad Waorani', 'Pueblo Chibuleo', 'Pueblo Kañari', 'Pueblo Karanki', 'Pueblo Kayambi', 'Pueblo Amazonía', 'Pueblo Kisapincha',
            'Pueblo Natabuela', 'Pueblo Otavalo', 'Pueblo Palta', 'Pueblo Panzaleo', 'Pueblo Pasto', 'Pueblo Puruwá', 'Pueblo Salasaka', 'Pueblo Saraguro',
            'Pueblo Tomabela', 'Pueblo Waranka', 'Pueblo Afroecuatoriano', 'Pueblo Huancavilca', 'Pueblo Manta', 'Pueblo Montuvios'
        ];
        $index = 0;
        $html = "<form action='' method='post' name='valorForm' style='width=100%'>
        <div class='container'>
        
        
            ";
        while ($index != sizeof($datos)) {

            $ruta = "/wordpress/wp-content/uploads/Saberes/PueblosNacionalidades/" . $datos[$index];
            $html .= "
                <div class='Saber' width='30%' heigth='30%' style='background:url($ruta); background-size: cover;'>
                <div class='tituloSaber' >
                <h3>$nombres[$index]</h3>
            </div> 
                    <img src='$ruta' alt='$nombres[$index]'name='$nombres[$index]' value='$nombres[$index]' id='$nombres[$index]' 
                    onclick='vuela(this)' width='25%' heigth='25%'> 
                   
                
                </div>
                
                
            ";
            $index++;
        }
        $html .= "
               <input type='hidden' name='seleccionado' id='seleccionado'>
            
        </div>
        </form>
        <script>
        
        function vuela(coso){
            localStorage.setItem('NP',coso.name)
            document.getElementById('seleccionado').value=coso.name;
            document.valorForm.submit();
        }
        
        </script>
        ";

        return $html;
    }

    //DDRC-C: funcion para mostrar tipoGs de archivos que trabaja directamente con el shortcode para el front-end
    public function NacPueTipos()
    {
        //var_dump($_POST);
        // var_dump($_SESSION);
        $rutaA = "/wordpress/wp-content/uploads/Saberes/TiposImg/aud.png";
        $rutaI = "/wordpress/wp-content/uploads/Saberes/TiposImg/img.png";
        $rutaV = "/wordpress/wp-content/uploads/Saberes/TiposImg/vid.png";
        $rutaT = "/wordpress/wp-content/uploads/Saberes/TiposImg/txt.png";
        $html = "
        <h1 id='NPID'></h1><br>
        <form action='' method='post' name='typoForm'>
            <div class='container'>
                <div class='col-6' style='display:inline'>
                    <img src='$rutaA' alt='tipo_Archivo'name='Audio' onclick='typo(this)' width='25%' heigth='25%'>
                </div>
                <div class='col-6' style='display:inline'>
                    <img src='$rutaI' alt='tipo_Archivo'name='Imagen' onclick='typo(this)' width='25%' heigth='25%'>
                </div>
                <div class='col-6' style='display:inline'>
                    <img src='$rutaV' alt='tipo_Archivo'name='Video' onclick='typo(this)' width='25%' heigth='25%'>
                </div>
                <div class='col-6' style='display:inline'>
                    <img src='$rutaT' alt='tipo_Archivo'name='Texto' onclick='typo(this)' width='25%' heigth='25%'>
                </div>
                <input type='hidden' name='typoFile' id='typoFile'>
            </div>
        </form>
        <button> <a href='wordpress/repositorio'>Regresar al repositorio</a></button>
        <script>
        document.getElementById('NPID').innerHTML=localStorage.getItem('NP');
            function typo(coso){
                localStorage.setItem('NPType',coso.name);
                document.getElementById('typoFile').value=coso.name;
                document.typoForm.submit();
            }
        </script>";
        return $html;
    }
    //DDRC-C: funcion para mostrar saberes por nacionalidad y tipo que trabaja directamente con el shortcode para el front-end
    public function NacPueSpesificSet($NP, $TP)
    {
        //var_dump($_SESSION);
        $datos = $this->ObtenerDatosPor($NP, $TP);
        if ($datos == "not-found") {
            $html = '
            <div class="noData">
            <h1>No hay saberes de este tipo...</h1>
            <button><a href="wordpress/repositorio">Regresar al repositorio<a></button>
            <div>
            ';
        } else {
            $html = "
            <h1 id='TitleID'></h1><br>
            <form action='' method='post' name='selectForm'>";
            foreach ($datos as $key => $value) {
                $RealPath = explode('../', $value['RutaSaber']);
                $RealPath1 = "/wordpress/wp-content/" . $RealPath[sizeof($RealPath) - 1];
                if ($TP == 'Audio') {
                    $sourceFile = "<audio src='$RealPath1'  controls></audio><br>";
                } else if ($TP == 'Video') {
                    $sourceFile = "<video src='$RealPath1' controls width='300' heigth='300'></video><br>";
                } else if ($TP == 'Imagen') {
                    $sourceFile = "<img src='$RealPath1' width='300' heigth='300' alt='imagen del saber'><br>";
                } else if ($TP == 'Texto') {
                    $sourceFile = "<iFrame src='$RealPath1' width='300' heigth='300'></iFrame>";
                    /*
                Parte anterior del codigo que mostraba archivos de texto '.txt'

                <textarea name actual value style='resize:none' readonly cols='30' rows='10'>
                " .
                    // Abriendo el archivo
                    file_get_contents($value['RutaSaber'])
                    . "</textarea>*/
                }
                $titulo = $value['Titulo'];
                $Nacion = $value['NacionalidadoPueblo'];
                $descripcion = $value['Descripcion'];
                $temas = $value['TagsTematicas'];
                $id = $value['ID'];

                $html .= "<div class='col' style='display:inline-block' onclick='selec(this)' name='$id' value='$id' id='$id'>
                <center><h2>$titulo</h2></center>
                <h3 >$Nacion</h3>
                <pre  class='text'>$descripcion</pre>
                <p class='text'><b>Tematicas:</b> $temas</p>
                <br>
                $sourceFile
                </div>";
            }
            $html .= "
        <input type='hidden' name='selecFile' id='selecFile'>
        </form>
        <br>
        <button> <a href='wordpress/repositorio'>Regresar al repositorio</a></button>
        <script>
        document.getElementById('TitleID').innerHTML=localStorage.getItem('NP')+'--'+localStorage.getItem('NPType');
            function selec(coso){
                document.getElementById('selecFile').value=coso.id;
                document.selectForm.submit();
        
            }
        </script>";
        }
        return $html;
    }
    //DDRC-C: funcion para saberes especificos que trabaja directamente con el shortcode para el front-end
    public function NacPueEspecifico($NP, $KO, $id)
    {
        //var_dump($_SESSION);
        $datos = $this->ObtenerDatosEspecificos($id);
        $RealPath = explode('../', $datos['RutaSaber']);
        $RealPath1 = "/wordpress/wp-content/" . $RealPath[sizeof($RealPath) - 1];
        $titulo = $datos['Titulo'];
        $Nacion = $datos['NacionalidadoPueblo'];
        $descripcion = $datos['Descripcion'];
        $temas = $datos['TagsTematicas'];
        if ($KO == 'Audio') {
            $sourceFile = "<audio src='$RealPath1'  controls></audio><br>";
        } else if ($KO == 'Video') {
            $sourceFile = "<video src='$RealPath1' controls width='300' heigth='300'></video><br>";
        } else if ($KO == 'Imagen') {
            $sourceFile = "<img src='$RealPath1' width='300' heigth='300' alt='imagen del saber'><br>";
        } else if ($KO == 'Texto') {
            $sourceFile = "<iFrame src='$RealPath1' width='900' heigth='1500'></iFrame>";
        }

        $html = "<div class='col' style='display:block'>
        <center><h2 class='title'>$titulo</h2></center>
        <br>
        <h4 class='title1'>$Nacion</h4>
        <br><spacer>
        $sourceFile
        <br>
        <pre class='text'>$descripcion</pre>
        <p class='text'><b>Tematicas:</b> $temas</p>
        
        </div>
        <br>
        <button> <a href='wordpress/repositorio'>Regresar al repositorio</a></button>
        ";
        return $html;
    }
}
