$('.tabsPastoreo').each(function(){

    $(this).on('click',function(){

        $('#mapaLotes').hide()

    })

})

$('.mapaLote').each(function(){

    $(this).on('click',function(){
        $(`#celula${$(this).attr('id')}`).addClass('active')
        $('#mapaLotes').hide()

    })

})

$('.lotesPath').each(function(){

    $(this).on('click',function(){

        let id = $(this).attr('id')

        let url = 'ajax/pastoreo.ajax.php'

        let [lote,parcela] = id.split('.')

        let celula = 'Amarilla'

        if($(this).attr('cell') == 'orange'){
            celula = 'Naranja'
        }else if($(this).attr('cell') == 'red'){
            celula = 'Roja'
        }

        let cellColor = $(this).attr('cell')

        $.ajax({

            method:'POST',
            url,
            data:{
                id,
                accion:'mostrarData'
            }

        }).done(function(resp){

            let respuesta = JSON.parse(resp)

            $('#modalHistoricoParcela').modal('hide')
            $('#modalParcela').modal('show')

            $('#cabezeraModalPastoreo').removeClass()
            $('#cabezeraModalPastoreo').addClass(`bg-${cellColor}`)
            $('#cabezeraModalPastoreo').addClass(`widget-user-header`)
            $('#detalleCelula').html(celula)
            $('#detalleLote').html(lote)
            $('#detalleParcela').html(parcela)

            if(respuesta.length > 0){
                // MUESTRO DATOS DETALLE
                $('#datosParcela').removeClass('hidden')
                $('#noData').addClass('hidden')

                $('#entradaPlanificado').val(respuesta[0].ingresoPlanificado)
                $('#salidaPlanificado').val(respuesta[0].salidaPlanificado)

                $('#idRegistro').val(respuesta[0].id)

                let diferenciaDiasPlanificado = ''
                let diferenciaDiasReal = ''

                if(respuesta[0].ingresoReal != null){
                    
                    $('#entradaReal').val(respuesta[0].ingresoReal)
                    console.log('etnro por aca')
                    $("input[name='salidaReal']").removeAttr('readOnly')

                } else {

                    $('#entradaReal').removeAttr('readOnly')

                }

                if(respuesta[0].salidaReal != null){

                    $('#salidaReal').val(respuesta[0].salidaReal)
                    $('#salidaReal').attr('readOnly','readOnly')

                    let fecha1 = moment(respuesta[0].ingresoReal);
                    let fecha2 = moment(respuesta[0].salidaReal);
                    diferenciaDiasReal = fecha2.diff(fecha1, 'days');
                    $('#diasPastoreoReal').val(diferenciaDiasReal)
    
                }

                let fecha1 = moment(respuesta[0].ingresoPlanificado);
                let fecha2 = moment(respuesta[0].salidaPlanificado);
                diferenciaDiasPlanificado = fecha2.diff(fecha1, 'days');
                $('#diasPlanificado').val(diferenciaDiasPlanificado)                   

                $('#recuperacion').val(respuesta[0].recuperacion)

                // CARGO DATOS A TABLA
                respuesta.forEach(pastoreo => {
                    
                    let row = $(`<tr>
                                    <td>${pastoreo.lote}</td>
                                    <td>${pastoreo.parcela}</td>
                                    <td>${moment(pastoreo.ingresoPlanificado).format('DD-MM-YYYY')}</td>
                                    <td>${moment(pastoreo.salidaPlanificado).format('DD-MM-YYYY')}</td>
                                    <td>${diferenciaDiasPlanificado}</td>
                                    <td>${moment(pastoreo.ingresoReal).format('DD-MM-YYYY')}</td>
                                    <td>${moment(pastoreo.salidaReal).format('DD-MM-YYYY')}</td>
                                    <td>${diferenciaDiasReal}</td>
                                    <td>${pastoreo.recuperacion}</td>
                                </tr>`)

                    $('#tbodyHistoricoParcela').html('')        
                    $('#tbodyHistoricoParcela').html(row)        

                });

            }else{

                $('#datosParcela').addClass('hidden')
                $('#noData').removeClass('hidden')

            }

        })
    })

})

$('.mostrarRegistroParsela').each(function(){
    
    $(this).on('click',function(){


        if (!/Mobi|Android/i.test(navigator.userAgent)) {
            $('.modal-content').css('width','800px')
        }

        $('#modalParcela').modal('hide')
        $('#modalHistoricoParcela').modal('show')

        let divToDelete = $('#DataTables_Table_0_paginate').parent().prev();

        $('#DataTables_Table_0_paginate').parent().removeClass('col-sm-7')
        $('#DataTables_Table_0_paginate').parent().addClass('col-sm-12')

        if (divToDelete.length > 0) divToDelete.remove()

    })

})

$('#btnVolverDetalleParcela').on('click',function(){
    $('#modalHistoricoParcela').modal('hide')
    $('#modalParcela').modal('show')
})


$('.tablasModal').DataTable({
    "dom": 'lrtip',
    "responsive": true,
    "ordering": false,
    "lengthChange": false,
    "searching":false,
    "info":false,
    "language": {

		"sProcessing":     "Procesando...",
		"sLengthMenu":     "Mostrar _MENU_ registros",
		"sZeroRecords":    "No se encontraron resultados",
		"sEmptyTable":     "Ningún dato disponible en esta tabla",
		"sInfo":           "",
		"sInfoEmpty":      "",
		"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		"sInfoPostFix":    "",
		"sSearch":         "Buscar:",
		"sUrl":            "",
		"sInfoThousands":  ",",
		"sLoadingRecords": "Cargando...",
		"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Último",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		}

	}

});


