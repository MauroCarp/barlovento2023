

<div class="row">
    
    <div class="col-lg-5">

        <div class="row">

            <div class="col-lg-8">

                <div class="form-group">
                
                    <label>Planificaci&oacute;n</label>
                
                    <select class="form-control">
                
                        <option>option 1</option>
                        <option>option 2</option>
                        <option>option 3</option>
                        <option>option 4</option>
                        <option>option 5</option>
                
                    </select>
                
                </div>
                
            </div>

            <div class="col-lg-4">

                <div class="form-group" style="padding-top:5px">

                    <br>

                    <button id="btnCostosPlanificacion" class="btn btn-secondary" data-toggle="modal" data-target="#modalVerCostosInversion">
                    
                        <i class="fa fa-dollar" style="color:#3c8dbc;font-size:1.2em;"></i><b>&nbsp;Costos Inversi&oacute;n</b>
                    
                    </button>

                </div>

            </div>

        </div>
        
            
        <?php

        $campo = 'La Bety';

        $campoId = 'Bety';
        
        include 'infoPlanificacion.php';
        
        $campo = 'El Pichi';
        
        $campoId = 'Pichi';

        include 'infoPlanificacion.php';
        
        ?>

    </div>

    <div class="col-lg-7">

        <?php include "graficosPlanificacion.php"; ?>

    </div>

</div>

<?php

$idModalDetalle = 'betyFina';

include 'vistas\modulos\modales\agro\detalleCultivos.php';

$idModalDetalle = 'betyCobertura';

include 'vistas\modulos\modales\agro\detalleCultivos.php';

$idModalDetalle = 'betyGruesa';

include 'vistas\modulos\modales\agro\detalleCultivos.php';

$idModalDetalle = 'pichiFina';

include 'vistas\modulos\modales\agro\detalleCultivos.php';

$idModalDetalle = 'pichiCobertura';

include 'vistas\modulos\modales\agro\detalleCultivos.php';

$idModalDetalle = 'pichiGruesa';

include 'vistas\modulos\modales\agro\detalleCultivos.php';

?>