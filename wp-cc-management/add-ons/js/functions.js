//sentencias jquery que se ejecuta cuando el documento este totalmente cargado
jQuery(document).ready(function ($) {
    //sentencia que se ejecuta al dar click en en el Boton de crear
    // $("#btn_crear").click(function () { 
    //     //sentencia que muestra el modal creado con boostrap
    //     $("#modalCrear").modal('show');
    // });
    // $("#AC_1").click(function () {
    //     $('#btnCambiante').html('Subir Saber');
    //     $("#modalCrear").modal('show');
    //     return false;
    // });
    // $("#AC_2").click(function () {
    //     $('#btnCambiante').html('Actualizar Saber');
    //     $("#modalCrear").modal('show');
    //     return false;
    // });
    // $("#AC_3").click(function () {
    //     $('#btnCambiante').html('Eliminar Saber');
    //     $("#modalCrear").modal('show');
    //     return false;
    // });
    // $("#AC_4").click(function () {
    //     $('#btnCambiante').html('Listar Saber');
    //     $("#modalCrear").modal('show');
    //     return false;
    // });

    var table = $("#tblst").DataTable({
        columnDefs: [
            { "width": "30%", "targets": 4 },
            { "width": "1%", "targets": 0 },
        ],
        scrollX: false,
        language: {
            processing: "Buscando Saberes...",
            search: "Buscar :",
            searchPlaceholder: "Busque un saber",
            lengthMenu: "Listar de a _MENU_ ",
            info: "Saber _START_ de _END_, de un total de _TOTAL_ saberes",
            infoEmpty: "Saber 0 de 0, no se ha encontrado el saber en la lista",
            infoFiltered: "(filtrado de _MAX_ saberes)",
            infoPostFix: "",
            loadingRecords: "Cargando Saberes...",
            zeroRecords: "No se ha encontrado ningun saber que concuerde con _TOTAL",
            emptyTable: "No hay registros de Saberes",
            paginate: {
                first: "Primer",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": se ordenara ascendentemente",
                sortDescending: ": se ordenara descendentemente"
            }
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
    });


});