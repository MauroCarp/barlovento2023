<?php

include 'cajasImpositivo.php';

?>

<div class="row">
    
    <div class="col-lg-4">
                
        <div class="box box-success">

            <div class="box-header with-border">

                <h3 class="box-title">Saldo Iva</h3>

                <div class="box-tools pull-right" bis_skin_checked="1">

                    <button type="button" class="btn btn-box-tool zoomGraficos" data-modal="zGraficoSaldoIva" data-widget="zoom"><i class="fa fa-search-plus"></i>
                    </button>

                </div>


            </div>


            <div class="box-body">

                <div class="chart">

                    <canvas id="saldoIva"></canvas>

                </div>

            </div>

        </div>
        
    </div>
            
    <div class="col-lg-4">
                
        <div class="box box-success">

            <div class="box-header with-border">
                
                <h3 class="box-title">Sueldos 1 + 2 / Ventas</h3>

                <div class="box-tools pull-right" bis_skin_checked="1">

                    <button type="button" class="btn btn-box-tool zoomGraficos" data-modal="zGraficoSueldos12" data-widget="zoom"><i class="fa fa-search-plus"></i>
                    </button>

                </div>
                
            </div>
            
            <div class="box-body">
                
                <div class="chart">

                    <canvas id="sueldos12Ventas" style="height:100px"></canvas>
                
                </div>

            </div>

        </div>

    </div>
            
    <div class="col-lg-4">
                
        <div class="box box-success">

            <div class="box-header with-border">
                
                <h3 class="box-title">Sueldos 1 + 2 + Honorarios / Ventas</h3>

                <div class="box-tools pull-right" bis_skin_checked="1">

                    <button type="button" class="btn btn-box-tool zoomGraficos" data-modal="zGraficoSueldos12Honorarios" data-widget="zoom"><i class="fa fa-search-plus"></i>
                    </button>

                </div>
                
            </div>
            
            <div class="box-body">
                
                <div class="chart">

                    <canvas id="sueldos12HonorariosVentas" style="height:100px"></canvas>
                
                </div>

            </div>

        </div>

    </div>

</div>

<?php

$tituloGrafico = 'Saldo IVA';
$idGraficoModal = 'graficoSaldoIvaModal';
$idGrafico = 'idGraficoSaldoIva';

include 'graficoContable.modal.php';

$tituloGrafico = 'Sueldos 1 + 2 / Ventas';
$idGraficoModal = 'graficoSueldo12Modal';
$idGrafico = 'idGraficoSueldo12';

include 'graficoContable.modal.php';

$tituloGrafico = 'Sueldos 1 + 2 + Horarios / Ventas';
$idGraficoModal = 'graficoSueldo12HonorarioModal';
$idGrafico = 'idGraficoSueldo12Honorario';

include 'graficoContable.modal.php';