<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."Html.php";
include_once LIB_PATH."HtmlTableDg.php";
include_once MDL_PATH."Tag.php";
include_once MDL_PATH."Profesional.php";

/**
 * Controller: MstController
 * @package SGi_Controllers
 */
class MstController extends Controller
{
    function home($auth)
    {
        $this->addTitle('Home');

        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('ver',$arr);
    }

    function tags($auth)
    {
        $this->addTitle('Tags');
    
        if (!$auth->isAdmin())
        {
            $this->addError('No esta autorizado a visualizar esta pagina.');
            return null;
        }
    
        $tag = new Tag();

        $ds = $tag->getDataSet(null,'inactivo,tag');
        
        $dg = new HtmlTableDg();
        $dg->addHeader('Nombre');
        $dg->addHeader('Acciones',$class=null,$width='150px');
        $dg->addHeader('Activo',$class=null,$width='100px',$align='center');

        if (!empty($ds))
        {
            foreach ($ds as $rw)
            {
                $className = '';
                if ($rw['inactivo'])
                    $className .= 'text-danger ';
                if (!empty($rw['sysid']))
                    $className .= 'text-info ';
                $htmlNombre = $rw['tag'];
                $htmlAcciones = '';
                $htmlActivo = '';
                if (empty($rw['sysid']))
                {
                    if (!$rw['inactivo'])
                        $htmlActivo = '<button onclick="toogleInactivo('.$rw['idtag'].')" id="arBtn" class="btn btn-sm btn-success">
                            <span class="glyphicon glyphicon-ok"></span>
                            </button>';
                    else
                        $htmlActivo = '<button onclick="toogleInactivo('.$rw['idtag'].')" id="arBtn" class="btn btn-sm btn-danger">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                            </button>';
                    
                }

                $dg->addRow(array($htmlNombre,$htmlAcciones,$htmlActivo),$className);
            }
        }
        $htmlNombre = '<input class="form-control" id="new_tag" name="new_tag">';
        $htmlAcciones = '<button onclick="addTag()" id="arBtn" class="btn btn-sm btn-primary">
                            Agregar
                            </button>';
        $htmlActivo = '';
        $dg->addRow(array($htmlNombre,$htmlAcciones,$htmlActivo));

    
        $arr['data'] = $dg->get();
        $arr['hidden'] = '';
    
        $this->addView('app/tags',$arr);
    }    
}
