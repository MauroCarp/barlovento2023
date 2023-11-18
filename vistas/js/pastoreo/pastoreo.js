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
            console.log(respuesta)

            $('#modalParcela').modal('show')
            $('#cabezeraModalPastoreo').removeClass()
            $('#cabezeraModalPastoreo').addClass(`bg-${cellColor}`)
            $('#cabezeraModalPastoreo').addClass(`widget-user-header`)
            $('#detalleCelula').html(celula)
            $('#detalleLote').html(lote)
            $('#detalleParcela').html(parcela)

            if(respuesta){
                $('#datosParcela').removeClass('hidden')
                $('#noData').addClass('hidden')

                $('#entradaPlanificado').val(respuesta.ingresoPlanificado)
                $('#salidaPlanificado').val(respuesta.salidaPlanificado)

                $('#idRegistro').val(respuesta.id)

                if(respuesta.ingresoReal != null){
                    
                    $('#entradaReal').val(respuesta.ingresoReal)
                    $('#salidaReal').removeAttr('readOnly')

                } else {

                    $('#entradaReal').removeAttr('readOnly')

                }

                if(respuesta.salidaReal != null){

                    $('#salidaReal').val(respuesta.salidaReal)
                    $('#salidaReal').attr('readOnly','readOnly')

                    let fecha1 = moment(respuesta.ingresoReal);
                    let fecha2 = moment(respuesta.salidaReal);
                    let diferenciaDias = fecha2.diff(fecha1, 'days');
                    $('#diasPastoreoReal').val(diferenciaDias)
    
                }

                let fecha1 = moment(respuesta.ingresoPlanificado);
                let fecha2 = moment(respuesta.salidaPlanificado);
                let diferenciaDias = fecha2.diff(fecha1, 'days');
                $('#diasPlanificado').val(diferenciaDias)

                    

                $('#recuperacion').val(respuesta.recuperacion)




            }else{

                $('#datosParcela').addClass('hidden')
                $('#noData').removeClass('hidden')

            }

        })
    })

})