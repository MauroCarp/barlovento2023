<div class="row">
    
    <div class="col-xs-4 col-md-4 col-lg-2 ">
        <img src="vistas/img/pastoreo/zoom<?=$celula?>.png" alt="" width="95%">
    </div>

</div>


<?php if($celula == 'Amarilla'){ ?>

<div class="row" style="margin-left:15px">

    <div class="col-lg-5 col-md-6 col-xs-12" style="background-image:url(vistas/img/pastoreo/celula<?=$celula?>1.png);background-repeat:no-repeat;padding-left:0;">

    <?php

        $path = 'vistas/img/pastoreo/celula' . $celula . '1.svg';

        $contenido_svg = file_get_contents($path);

        echo $contenido_svg;

    ?>

    </div>

</div> 

<div class="row" style="margin-left:15px">

    <div class="col-lg-5 col-md-6 col-xs-12" style="background-image:url(vistas/img/pastoreo/celula<?=$celula?>2.png);background-repeat:no-repeat;padding-left:0;">

    <?php
    
    $path = 'vistas/img/pastoreo/celula' . $celula . '2.svg';

    $contenido_svg = file_get_contents($path);

    echo $contenido_svg; 

    ?>
    </div>

</div>

<?php } else { ?>

<div class="row" style="margin-left:15px">

    <div class="col-lg-8 col-xs-11" style="background-image:url(vistas/img/pastoreo/celula<?=$celula?>.png);background-repeat:no-repeat;padding-left:0;">

    <?php   

        $path = 'vistas/img/pastoreo/celula' . $celula . '.svg';

        $contenido_svg = file_get_contents($path);

        echo $contenido_svg;

    ?> 

    </div>
    
</div>

<?php } ?>
