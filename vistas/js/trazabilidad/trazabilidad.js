
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

