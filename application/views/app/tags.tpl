{{divPop}}
{{actionBar}}
{{data}}
{{tabs}}
{{hidden}}
<div id="PopUpContainer" ></div>

<script language="javascript" >

    $(document).ready( function () {

    });


    function toogleInactivo(idtag)
    {
        CtrlAjax.sendCtrl("app","tag","toogleInactivo","idtag="+idtag);    
    }

    function addTag()
    {
        var new_tag = $('#new_tag').val();
        if ($('#new_tag').val())
        {
            if (confirm('Desea agregar el tag'+"\n"+new_tag))
                CtrlAjax.sendCtrl("app","tag","crear");    
        }
        else
        {
            alert('Se debe especificar un nombre para el nuevo Tag');
        }
    }
    



</script>
