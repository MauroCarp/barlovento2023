<?php

include 'cajasEconomico.php';


?>

<div class="row">


    <div class="col-lg-4">
        
        <div class="row">

            <div class="col-lg-12">
                
                <div class="box box-success">

                    <div class="box-header with-border">

                        <h3 class="box-title">Ventas Agricultura</h3>

                        <div class="box-tools pull-right" bis_skin_checked="1">

                            <button type="button" class="btn btn-box-tool zoomGraficos" data-modal="zGraficoVentas<?=$campo?>" data-widget="zoom"><i class="fa fa-search-plus"></i>
                            </button>

                        </div>
                    </div>


                    <div class="box-body">

                        <div class="chart">

                            <canvas id="ventasChart<?=$campo?>"></canvas>

                        </div>

                    </div>
                
                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">
    
        <div class="row">

            <div class="col-lg-12">
            
                <div class="box box-success">

                    <div class="box-header with-border">

                        <h3 class="box-title">M/V | BAAI</h3>

                        <div class="box-tools pull-right" bis_skin_checked="1">

                            <button type="button" class="btn btn-box-tool zoomGraficos" data-modal="zGraficoMargenVentas<?=$campo?>" data-widget="zoom"><i class="fa fa-search-plus"></i>
                            </button>

                        </div>

                    </div>


                    <div class="box-body">

                        <div class="chart">

                            <canvas id="margenVentasChart<?=$campo?>"></canvas>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">
    
        <div class="row">

            <div class="col-lg-12">
            
                <div class="box box-success">

                    <div class="box-header with-border">

                        <h3 class="box-title">Renta/Activo</h3>

                    </div>


                    <div class="box-body">

                        <div class="chart">
                        
                            <canvas id="rentabilidadEconomicaChart<?=$campo?>"></canvas>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="row">


    <div class="col-lg-4">
        
        <div class="row">

            <div class="col-lg-12">
                
                <div class="box box-success">

                    <div class="box-header with-border">

                        <h3 class="box-title">Ventas Ganaderia</h3>

                        <div class="box-tools pull-right" bis_skin_checked="1">

                            <button type="button" class="btn btn-box-tool zoomGraficos" data-modal="zGraficoVentas2<?=$campo?>" data-widget="zoom"><i class="fa fa-search-plus"></i>
                            </button>

                        </div>
                    </div>


                    <div class="box-body">

                        <div class="chart">

                            <canvas id="ventasChart2<?=$campo?>"></canvas>

                        </div>

                    </div>
                
                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">
        
        <div class="row">

            <div class="col-lg-12">
                
                <div class="box box-success">

                    <div class="box-header with-border">

                        <h3 class="box-title">Ventas Ganader&iacute;a por L&iacute;neas de Producto</h3>

                        <div class="box-tools pull-right" bis_skin_checked="1">

                            <button type="button" class="btn btn-box-tool zoomGraficos" data-modal="zGraficoGanaderia<?=$campo?>" data-widget="zoom"><i class="fa fa-search-plus"></i>
                            </button>

                        </div>
                    </div>


                    <div class="box-body">

                        <div class="chart">

                            <canvas id="ventasGanaderiaChart<?=$campo?>"></canvas>

                        </div>

                    </div>
                
                </div>

            </div>

        </div>

    </div>

</div>
<?php

$tituloGrafico = 'Ventas Agricultura';
$idGraficoModal = 'graficoVentaModal' . $campo;
$idGrafico = 'idGraficoVentas'  . $campo;
include 'graficoContable.modal.php';

$tituloGrafico = 'Ventas Ganaderia';
$idGraficoModal = 'graficoVenta2Modal' . $campo;
$idGrafico = 'idGraficoVentas2'  . $campo;
include 'graficoContable.modal.php';

$tituloGrafico = 'Ventas Ganaderia por Lineas de Producto';
$idGraficoModal = 'graficoGanaderiaModal'  . $campo;
$idGrafico = 'idGraficoGanaderia2'  . $campo;
include 'graficoContable.modal.php';

$tituloGrafico = 'M/V - BAAI';
$idGraficoModal = 'graficoMargenVentaModal' . $campo;
$idGrafico = 'idGraficoMargenVentas' . $campo;
include 'graficoContable.modal.php';

?>


