
$('#btnPaso1').on('click',function(){
    
    if($('#nombreFaena').val() == '' || $('#fechaFaena').val() == ''){

        swal({
            type: "error",
            title: "Nombre y fecha son obligatorios",
            showConfirmButton: true,
            confirmButtonText: "Cerrar"
            })

        return
    }

    $('#faenaPaso1').hide(200)
    $('#faenaPaso2').show(200)
})

$('#btnVolver').on('click',function(){
    $('#faenaPaso1').show(200)
    $('#faenaPaso2').hide(200)
})

$('.tablaFaenas').on('click','.btnEliminarFaena',function(){

    let idFaena = $(this).attr('idFaena')

    let node = $(this)
    
    swal({
        title: '¿Está seguro de eliminar?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar!'
    }).then(function(result){

        if(result.value){

            let url = 'ajax/trazabilidad.ajax.php'
            let data = `accion=eliminar&idFaena=${idFaena}`

            $.ajax({
                method:'post',
                url,
                data,
                success:function(response){
                    console.log(response)

                    swal({

                        type: "success",
                        title: "¡El registro fue eliminado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"

                    }).then(function() {
                        node.closest('tr').remove()
                    })


                }
            })

        }

    })

})