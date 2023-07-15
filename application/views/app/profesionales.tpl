{{divPop}}
{{actionBar}}
<div class="container">
  <div class="row">
    <div class="col" style="text-align: right;">
      <a class="btn btn-primary btn-sm menu-admin" href="app.Profesional.editar+">Crear nuevo</a>
    </div>
  </div>
</div>
{{data}}
{{tabs}}
{{hidden}}
<div id="PopUpContainer" ></div>

<script language="javascript" >

    $(document).ready( function () {

    });


    function toogleInactivo(idprofesional)
    {
        CtrlAjax.sendCtrl("app","profesional","toogleInactivo","idprofesional="+idprofesional);    
    }

    function editar(idprofesional)
    {
        goTo("app.profesional.editar+idprofesional="+idprofesional);    
    }

        



</script>