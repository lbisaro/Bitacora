{{divPop}}
{{actionBar}}
<div class="container">
  <div class="row">
    <div class="col" style="text-align: right;">
      <a class="btn btn-primary btn-sm menu-admin" href="app.Paciente.editar+">Crear nuevo</a>
    </div>
  </div>
</div>
<br/>
{{data}}
{{tabs}}
{{hidden}}
<div id="PopUpContainer" ></div>

<script language="javascript" >

    $(document).ready( function () {

    });


    function toogleInactivo(idpaciente)
    {
        CtrlAjax.sendCtrl("app","paciente","toogleInactivo","idpaciente="+idpaciente);    
    }

    function editar(idpaciente)
    {
        goTo("app.paciente.editar+idpaciente="+idpaciente);    
    }

    function ficha(idpaciente)
    {
        goTo("app.paciente.ficha+idpaciente="+idpaciente);    
    }

        



</script>