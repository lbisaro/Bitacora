<style type="text/css">
    #log tr {
        cursor: pointer;
    }
</style>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <h5 class="bd-title" >
        Registro de eventos  
      </h5>
    </div>
  </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm">
            <div class="form-group">
                <label for="idpaciente">Paciente</label>
                <div class="input-group mb-2">
                    <select class="form-control" id="idpaciente" onchange="filtrar()">
                      {{idpaciente_opt}}
                    </select>
                </div>
            </div>
            
        </div>        
        {{html_select_profesional}}      
        <div class="col-sm">
            <div class="form-group">
                <label for="from-to">Fecha desde</label>
                <div class="input-group mb-2">
                    <input class="form-control" id="from" value="{{from}}" onchange="value = mkFecha(this.value); filtrar()" />
                </div>
            </div>
            
        </div>
        <div class="col-sm">
            <div class="form-group">
                <label for="from-to">Fecha hasta</label>
                <div class="input-group mb-2">
                    <input class="form-control" id="to" value="{{to}}" onchange="value = mkFecha(this.value); filtrar()" />
                </div>
            </div>
            
        </div>
    </div>

</div>
<div id="eventos">
</div>

<script language="javascript" >

    $(document).ready( function () {
        filtrar();
    });

    function formatTabla()
    {
        $('#log tr td').on('click',function () {
            var id = $(this).parent().attr('id');
            goTo("app.evento.ver+id="+id+"&returnTo=app.evento.home");
        })
    }
    function filtrar()
    {
        CtrlAjax.sendCtrl("app","evento","consultar");    
    }
</script>