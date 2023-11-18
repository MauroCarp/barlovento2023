<div class="row">
    
    <div class="col-sm-12 visible-xs">
        <img src="vistas/img/pastoreo/zoomAmarilla.png" alt="">
    </div>
    <div class="col-lg-1">
    </div>

    <?php

        if($celula == 'Amarilla'){ ?>

            <div class="col-lg-4 col-sm-12" style="background-image:url(vistas/img/pastoreo/celula<?=$celula?>1.png);background-repeat:no-repeat;padding-left:0;">
    
        <?php

            $path = 'vistas/img/pastoreo/celula' . $celula . '1.svg';

            $contenido_svg = file_get_contents($path);

            echo $contenido_svg;

        ?>

            </div>

            <div class="col-lg-4 col-sm-12" style="background-image:url(vistas/img/pastoreo/celula<?=$celula?>2.png);background-repeat:no-repeat;padding-left:0;">

        <?php
        
            $path = 'vistas/img/pastoreo/celula' . $celula . '2.svg';

            $contenido_svg = file_get_contents($path);

            echo $contenido_svg;

        } else { ?>

            <div class="col-lg-4 col-sm-12" style="background-image:url(vistas/img/pastoreo/celula<?=$celula?>.png);background-repeat:no-repeat;padding-left:0;">

        <?php   
            $path = 'vistas/img/pastoreo/celula' . $celula . '.svg';

            $contenido_svg = file_get_contents($path);

            // Imprimir el contenido del SVG
            echo $contenido_svg;

        }

        ?>

        </div>

    <div class="col-lg-2">

        <img src="vistas/img/pastoreo/zoom<?=$celula?>.png" alt="" width="100%">

    </div>

</div>
