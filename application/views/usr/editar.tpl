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
        <label for="username">Username</label>
        <div class="input-group mb-2">
            <input type="text" class="form-control" value="{{username}}" id="username" >
        </div>
      </div>

      <div class="form-group">
        <label for="idperfil">Perfil</label>
        <div class="input-group mb-2">
          <select class="form-control" id="idperfil" >
              {{idperfil_opt}}
            </select>
        </div>
      </div>



        <div class="form-group" id="btnGrabar">
            <button onclick="grabar()" class="btn btn-success" >Grabar</button>
        </div>
      </div>

    </div>
  </div>

</div>
<input type="hidden" name="idusuario" id="idusuario" value="{{idusuario}}">

<script language="javascript" >

    $(document).ready( function () {

    });


    function grabar()
    {
        CtrlAjax.sendCtrl("usr","usr","grabar");

    }
</script>

