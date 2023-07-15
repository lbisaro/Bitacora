<style type="text/css">
  .data {
    font-weight: bolder;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col">
      <h5 class="bd-title" >
        {{ayn}}  
      </h5>
    </div>
  </div>

<div class="container">

  <div class="row">
    <div class="col">
      

      <div class="form-group">
        <label for="fecha">Fecha</label>
        <div class="input-group mb-2">
            <input type="text" class="form-control" value="{{fecha}}" id="fecha">
        </div>
      </div>

      <div class="form-group">
        <label for="idtag">Tag</label>
        <div class="input-group mb-2">
            <select class="form-control" id="idtag" >
              {{idtag_opt}}
            </select>
        </div>
      </div>

      <div class="form-group">
        <label for="idprofesional">Profesional</label>
        <div class="input-group mb-2">
            <select class="form-control" id="idprofesional" >
              {{idprofesional_opt}}
            </select>
        </div>
      </div>
      
      <div class="form-group">
        <label for="notas">Notas</label>
        <div class="input-group mb-2">
            <textarea type="text" class="form-control" rows="10" id="notas" ></textarea>
        </div>
      </div>

        <div class="form-group" id="btnGrabar">
            <button onclick="grabar()" class="btn btn-success" >Grabar</button>
            <a href="app.paciente.ficha+idpaciente={{idpaciente}}" class="btn btn-danger" >Cancelar</a>
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
        CtrlAjax.sendCtrl("app","paciente","addLog");
    }

</script>

