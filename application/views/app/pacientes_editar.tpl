<style type="text/css">
  .data {
    font-weight: bolder;
  }
</style>
<div class="container">

  <div class="row">
    <div class="col">
      

      <div class="form-group">
        <label for="ayn">Apellido y Nombre</label>
        <div class="input-group mb-2">
            <input type="text" class="form-control" value="{{ayn}}" id="ayn">
        </div>
      </div>

      <div class="form-group">
        <label for="mail">Mail</label>
        <div class="input-group mb-2">
            <input type="text" class="form-control" value="{{mail}}" id="mail" >
        </div>
      </div>
      
      <div class="form-group">
        <label for="telefono">Telefono</label>
        <div class="input-group mb-2">
            <input type="text" class="form-control" value="{{telefono}}" id="telefono" >
        </div>
      </div>

        <div class="form-group" id="btnGrabar">
            <button onclick="grabar()" class="btn btn-success" >Grabar</button>
        </div>
      </div>

    </div>
  </div>

</div>
<input type="hidden" name="idpaciente" id="idpaciente" value="{{idpaciente}}">

<script language="javascript" >

    $(document).ready( function () {

    });


    function grabar()
    {
        CtrlAjax.sendCtrl("app","paciente","grabar");
    }

    function ficha()
    {
        CtrlAjax.sendCtrl("app","paciente","ficha");
    }
</script>

