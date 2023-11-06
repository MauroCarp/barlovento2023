

<div class="row">
    
    <div class="col-lg-5">

        <div class="row" sty>

            <div class="col-lg-8">

                <div class="form-group">
                
                    <label>Planificaci&oacute;n</label>
                
                    <select class="form-control" name="cargaPlanificacion">

                        <?php
                            $cargas = ControladorAgro::ctrMostrarCargasPorCampania($_GET['campania']);

                            foreach ($cargas as $carga) { ?>
                                
                             <option value="<?=$carga['tipo']?>">Carga <?=date('d-m-Y',strtotime($carga['created_at']))?> <?=($carga['tipo'] == 0) ? '(Original)' : ''?> </option>   
                        <?php
                            }
                        ?>
                
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

        <div class="row">
            
            <div class="col-lg-6">
                
                <div class="info-box">

                    <span class="info-box-icon bg-aqua"><i class="fa fa-map-o"></i></span>      

                    <div class="info-box-content">

                        <span class="info-box-text">Hectareas Totales</span>
                        
                        <span class="info-box-number"><span id="totalHasPlanificadas"></span> Has.</span>

                    </div>
        
                </div>

            </div>
     
            <div class="col-lg-6">
                
                <div class="info-box">

                    <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
                    <div class="info-box-content">
                    <span class="info-box-text">Inversion <br> Total Proyectada</span>
                    <span class="info-box-number">U$D <span id="totalInversionPlanificada"></span></span>
                    </div>
        
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