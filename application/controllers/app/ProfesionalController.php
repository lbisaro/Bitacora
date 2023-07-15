<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."Html.php";
include_once LIB_PATH."HtmlTableDg.php";
include_once MDL_PATH."Profesional.php";

/**
 * Controller: ProfesionalController
 * @package SGi_Controllers
 */
class ProfesionalController extends Controller
{
    function listar($auth)
    {
        $this->addTitle('Profesionales');
    
        if (!$auth->isAdmin())
        {
            $this->addError('No esta autorizado a visualizar esta pagina.');
            return null;
        }
    
        $prf = new Profesional();

        $ds = $prf->getDataSet(null,'inactivo,ayn');
        
        $dg = new HtmlTableDg();
        $dg->addHeader('Apellido y Nombre');
        $dg->addHeader('Mail');
        $dg->addHeader('Usuario');
        $dg->addHeader('Telefono');
        $dg->addHeader('Cargo');
        $dg->addHeader('Acciones');
        $dg->addHeader('Activo');

        if (!empty($ds))
        {
            foreach ($ds as $rw)
            {
                $className = '';
                if ($rw['inactivo'])
                    $className = 'text-danger';
                $htmlAcciones = '<button onclick="editar('.$rw['idprofesional'].')" id="btnEditar" class="btn btn-sm btn-secondary">
                        <span class="glyphicon glyphicon-pencil"></span>
                        </button>';
                if (!$rw['inactivo'])
                    $htmlActivo = '<button onclick="toogleInactivo('.$rw['idprofesional'].')" id="arBtn" class="btn btn-sm btn-success">
                        <span class="glyphicon glyphicon-ok"></span>
                        </button>';
                else
                    $htmlActivo = '<button onclick="toogleInactivo('.$rw['idprofesional'].')" id="arBtn" class="btn btn-sm btn-danger">
                        <span class="glyphicon glyphicon-ban-circle"></span>
                        </button>';

                $dg->addRow(array($rw['ayn'],$rw['mail'],$rw['username'],$rw['telefono'],$rw['cargo'],$htmlAcciones,$htmlActivo),$className);
            }
        }
        
        $arr['data'] = $dg->get();
        $arr['hidden'] = '';
    
        $this->addView('app/profesionales',$arr);
    }

    function editar($auth)
    {
        $idprofesional = $_REQUEST['idprofesional'];
        if ($idprofesional)
            $this->addTitle('Editar Profesional');
        else
            $this->addTitle('Nuevo Profesional');

        if (!$auth->isAdmin())
        {
             $this->adderror('No esta autorizado a visualizar esta pagina.');
             return null;
        }

        if ($idprofesional)
        {
            $prf = new Profesional($idprofesional);
            if ($idprofesional != $prf->get('idprofesional'))
            {
                 $this->adderror('Se debe especificar un Id valido');
                 return null;
            }
            $arr['ayn'] = $prf->get('ayn');
            $arr['mail'] = $prf->get('mail');
            $arr['telefono'] = $prf->get('telefono');
            $arr['cargo'] = $prf->get('cargo');
            $arr['idprofesional'] = $idprofesional;
        }
   
        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('app/profesionales_editar',$arr);
    }

}
