//sentencias jquery que se ejecuta cuando el documento este totalmente cargado
jQuery(document).ready(function($){
    //sentencia que se ejecuta al dar click en en el voton de crear
    $("#btn_crear").click(function () { 
        //sentencia que muestra el modal creado con boostrap
        $("#modalCrear").modal('show');
    });
    $("#AC_1").click(function () {
        $('#btnCambiante').html('Subir Saber');
        $("#modalCrear").modal('show');
        return false;
    });
    $("#AC_2").click(function () {
        $('#btnCambiante').html('Actualizar Saber');
        $("#modalCrear").modal('show');
        return false;
    });
    $("#AC_3").click(function () {
        $('#btnCambiante').html('Eliminar Saber');
        $("#modalCrear").modal('show');
        return false;
    });
    $("#AC_4").click(function () {
        $('#btnCambiante').html('Listar Saber');
        $("#modalCrear").modal('show');
        return false;
    });
    document.getElementById('').addEventListener('click',function () {
        this.getAttribute('value');
    });
});