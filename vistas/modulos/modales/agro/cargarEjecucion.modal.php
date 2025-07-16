<div id="modalCargarEjecucion" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data" id="formCarga">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Carga de Ejecuci&oacute;n por Lotes</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">
            
          
          <div class="form-group">

            <label for="selectEtapa">Etapa</label>

            <select class="form-control" id="selectEtapa" name="etapaEjecucion">
              <option value="fina">Al 31 de Diciembre</option>
              <option value="gruesa">Al 31 de Mayo</option>
            </select>

          </div>

          <div class="box-body" id="formEjecucion">
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary" id="btnCargar" name="btnCargar" data-carga="">Cargar Ejecuci&oacute;n de lotes</button>

        </div>

      </form>

    </div>

  </div>

</div>

<?php

// if($_SESSION['perfil'] == 'Agro' OR $_SESSION['perfil'] == 'Administrador Agro')
//   $cargarArchivo = new ControladorAgro();

// if($_SESSION['perfil'] == 'Contable' OR $_SESSION['perfil'] == 'Administrador Contable')
//   $cargarArchivo = new ControladorContable();

// if($_SESSION['perfil'] == 'Pastoreo' OR $_SESSION['perfil'] == 'Administrador Pastoreo')
//   $cargarArchivo = new ControladorPastoreo();


// $cargarArchivo->ctrCargarArchivo();


?>

