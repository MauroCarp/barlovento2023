<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Trazabilidad
      
      <small>Panel de Control</small>
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Trazabilidad</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-body">
          
        <div class="row">

          <div class="col-lg-6">

            <div class="box box-info">

              <div class="box-header with-border">

                <h3 class="box-title">Nueva Faena</h3>

              </div>

              <div class="box-body">

                <form method="post" enctype="multipart/form-data">

                  <div class="row" id="faenaPaso1">

                    <div class="col-lg-8">

                      <div class="form-group">
      
                        <label for="nombreFaena">Nombre</label>
                        <input type="text" class="form-control" name="nombreFaena" id="nombreFaena" required>
      
                      </div>
      
                      <div class="form-group">
      
                        <label>Fecha:</label>
      
                        <div class="input-group date">
      
                          <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                          </div>
      
                          <input type="date" class="form-control pull-right" id="fechaFaena" name="fechaFaena" required>
                        </div>
      
                      </div>

                    </div>

                    <div class="col-lg-4" align="center">

                      <button style="border-radius:5px;padding:5px 20px;background-color:transparent;font-family:calibri;color:rgb(200,200,200);font-size:2em;" id="btnPaso1">
                        <i class="fa fa-arrow-right" style="font-size:5em;"></i><br>
                        <b>Continuar</b>
                      </button>

                    </div>

                  </div>

                  <div class="row" style="display:none" id="faenaPaso2">

                    <div class="col-lg-8">

                      <div class="form-group">

                        <label for="excelTD">Excel Toma Decisión</label>
                        <input type="file" id="excelTD" required>

                      </div>

                      <div class="form-group">

                        <label for="excelWC">Excel Wincampo</label>
                        <input type="file" id="excelWC" required>

                      </div>

                      <div class="form-group">

                        <label for="excelTrazabilidad">Excel Trazabilidad</label>
                        <input type="file" id="excelTrazabilidad" required>

                      </div>

                    </div>

                    <div class="col-lg-4" align="center">

                      <button style="border-radius:5px;padding:5px 20px;background-color:transparent;font-size:2em;font-family:calibri;color:rgb(200,200,200);margin-bottom:5px" id="btnVolver">
                        <i class="fa fa-arrow-left"></i>
                        <b>Volver</b>
                      </button>

                      <button type="submit" style="border-radius:5px;padding:5px 20px;background-color:transparent;font-family:calibri;color:rgb(200,200,200);font-size:2em;" id="btnPaso2">
                        <i class="fa fa-upload" style="font-size:5em;"></i><br>
                        <b>Cargar</b>
                      </button>

                    </div>

                  </div>
                  
                </form>
                
              </div>

            </div>

          </div>

          <div class="col-lg-6">

            <div class="box box-info">

              <div class="box-header with-border">

                <h3 class="box-title">Registro de faenas</h3>

              </div>

              <div class="box-body">

                <table class="table table-bordered table-striped dt-responsive tablas">
                  
                  <thead>
                  
                  <tr>
                    
                    <th>Faena</th>
                    <th>Fecha</th>
                    <th width="250px"></th>
        
                  </tr> 
        
                  </thead>
        
                  <tbody>
        
                  <?php
        
                    $faenas = ControladorTrazabilidad::ctrMostrarFaenas();
               
                    foreach ($faenas as $faena){
                      $fecha = date('d-m-Y',strtotime($faena['fecha']));
        
                      echo ' <tr>
                              <td>' . $faena['nombre'] . '</td>
                              <td>' . $fecha . '</td>
                              <td>
        
                                <div class="btn-group" role="group" aria-label="...">
        
                                  <div class="btn-group">
                                    <button class="btn btn-info btn-xl dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Reportes <i class="fa fa-files-o"></i> | &nbsp  <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li>  
                                        <a href="index.php?ruta=trazabilidad/reporte1&idFaena=' . $faena['id'] . '" target="_blank">Reporte 1</a>  
                                      </li>  
                                      <li>  
                                        <a href="index.php?ruta=trazabilidad/reporte2&idFaena=' . $faena['id'] . '" target="_blank">Reporte 2</a>  
                                      </li>  
                                    </ul>
                                  </div>
                                  
                                  <button class="btn btn-danger btnEliminarFaena" idFaena="' . $faena['id'] . '"><i class="fa fa-times"></i></button>
                                  
                                  
                                  </div>
                                  
                              </td>
                            </tr>';
                      }
                  ?> 
        
                  </tbody>
        
                </table>

              </div>

          </div>

        </div>

      </div>

      </div>

  </section>
 
</div>

<?php 

$nuevaFaena = new ControladorTrazabilidad();
$nuevaFaena-> ctrNuevaFaena();

?>