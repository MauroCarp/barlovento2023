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

        $.ajax({
            method:'POST',
            url,
            data:{

            }
        }).done(function(resp){
            console.log(resp)
        })
    })

})