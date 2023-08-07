<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."Html.php";
include_once LIB_PATH."HtmlTableDg.php";
include_once MDL_PATH."Tag.php";
include_once MDL_PATH."Paciente.php";
include_once MDL_PATH."Profesional.php";

/**
 * Controller: PacienteController
 * @package SGi_Controllers
 */
class PacienteController extends Controller
{
    function listar($auth)
    {
        $this->addTitle('Pacientes');
    
        $pct = new Paciente();

        if ($auth->isAdmin())
        {
            $ds = $pct->getDataSet(null,'inactivo,ayn');
        }
        else
        {
            $prf = new Profesional($auth->get('idprofesional'));
            $ds = $prf->getPacientesAsignados();
        }

        
        $dg = new HtmlTableDg();
        $dg->addHeader('Apellido y Nombre');
        $dg->addHeader('Mail');
        $dg->addHeader('Telefono');
        $dg->addHeader('Acciones');
        $dg->addHeader('Activo');

        if (!empty($ds))
        {
            foreach ($ds as $rw)
            {
                $className = '';
                if ($rw['inactivo'])
                    $className = 'text-danger';

                $htmlAcciones = '<button onclick="ficha('.$rw['idpaciente'].')" id="btnFicha" class="btn btn-sm btn-primary" title="Ver ficha">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        </button>&nbsp;';
                $htmlAcciones .= '<button onclick="editar('.$rw['idpaciente'].')" id="btnEditar" class="btn btn-sm btn-secondary" title="Editar datos">
                        <span class="glyphicon glyphicon-pencil"></span>
                        </button>';
                if ($auth->isAdmin())
                {
                    if (!$rw['inactivo'])
                        $htmlActivo = '<button onclick="toogleInactivo('.$rw['idpaciente'].')" id="arBtn" class="btn btn-sm btn-success">
                            <span class="glyphicon glyphicon-ok"></span>
                            </button>';
                    else
                        $htmlActivo = '<button onclick="toogleInactivo('.$rw['idpaciente'].')" id="arBtn" class="btn btn-sm btn-danger">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                            </button>';
                    
                }
                else
                {
                    if (!$rw['inactivo'])
                        $htmlActivo = '<span class="glyphicon glyphicon-ok text-success"></span>';
                    else
                        $htmlActivo = '<span class="glyphicon glyphicon-ban-circle text-danger"></span>';
                    
                }

                $dg->addRow(array($rw['ayn'],$rw['mail'],$rw['telefono'],$htmlAcciones,$htmlActivo),$className);
            }
        }
        
        $arr['data'] = $dg->get();
        $arr['hidden'] = '';
    
        $this->addView('app/pacientes',$arr);
    }    

    function editar($auth)
    {
        $idpaciente = $_REQUEST['idpaciente'];
        if ($idpaciente)
            $this->addTitle('Editar Paciente');
        else
            $this->addTitle('Nuevo Paciente');

        if (!$auth->checkPaciente($idpaciente))
        {
             $this->adderror('No esta autorizado a visualizar esta pagina.');
             return null;
        }

        if ($idpaciente)
        {
            $pct = new Paciente($idpaciente);
            $this->addTitle($pct->get('ayn'));
            if ($idpaciente != $pct->get('idpaciente'))
            {
                 $this->adderror('Se debe especificar un Id valido');
                 return null;
            }
            $arr['ayn'] = $pct->get('ayn');
            $arr['mail'] = $pct->get('mail');
            $arr['telefono'] = $pct->get('telefono');
            $arr['idpaciente'] = $idpaciente;
        }
   
        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('app/pacientes_editar',$arr);
    }

    function ficha($auth)
    {
        $idpaciente = $_REQUEST['idpaciente'];
        $this->addTitle('Ficha Paciente');

        $pct = new Paciente($idpaciente);
        if (!$idpaciente || $idpaciente != $pct->get('idpaciente'))
        {
             $this->adderror('Se debe especificar un Id valido');
             return null;
        }        

        if (!$auth->checkPaciente($idpaciente))
        {
            $this->adderror('No esta autorizado a visualizar esta pagina.');
            return null;
        }

        
        $this->addTitle($pct->get('ayn'));

        $arr['ayn'] = $pct->get('ayn');
        $arr['mail'] = $pct->get('mail');
        $arr['telefono'] = $pct->get('telefono');
        $arr['fecha_alta'] = $pct->get('fecha_alta');
        $arr['fecha_baja'] = '<span class="text-danger">'.$pct->get('fecha_baja').'</span>';
        $arr['idpaciente'] = $idpaciente;

        if (!$pct->isActivo())
            $arr['flag_inactivo'] = '<h3 class="text-danger text-center">Paciente Inactivo</h3>';
        
        $dg = new HtmlTableDg();
        $dg->setCaption('Eventos');
        $log = $pct->getLog();
        $dg->addHeader('Evento',$class=null,$width='20%');
        $dg->addHeader('Notas');
        foreach ($log as $rw)
        {
            $evento = dateToStr($rw['fecha']);
            if ($rw['profesional_ayn'])
                $evento .= '<br/><b>'.$rw['profesional_ayn'].'</b>';
            $evento .= '<br/><span class="text-secondary">'.$rw['username'].'</span>';

            $notas = '<b>'.$rw['tag'].'</b>';
            if ($rw['notas'])
                $notas .= '<br/>'.nl2br($rw['notas']);
            $dg->addRow(array($evento,$notas));
        }

        $arr['log'] = $dg->get();
        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('app/pacientes_ficha',$arr);
    }

    function addLog($auth)
    {
        $idpaciente = $_REQUEST['idpaciente'];
        $this->addTitle('Registrar evento');

        if (!$auth->checkPaciente($idpaciente))
        {
             $this->adderror('No esta autorizado a visualizar esta pagina.');
             return null;
        }

        $pct = new Paciente($idpaciente);
        if (!$idpaciente || $idpaciente != $pct->get('idpaciente'))
        {
             $this->adderror('Se debe especificar un Id valido');
             return null;
        }
        
        $this->addTitle($pct->get('ayn'));

        $arr['ayn'] = $pct->get('ayn');
        $arr['idpaciente'] = $idpaciente;



        $tag = new Tag();
        $tagAct = $tag->getToAddLog();
        if (!empty($tagAct))
        {
            $arr['idtag_opt'] = '<option value="0">Seleccionar</option>';;
            foreach ($tagAct as $rw)
                $arr['idtag_opt'] .= '<option value="'.$rw['idtag'].'">'.$rw['tag'].'</option>';
        }

        if ($auth->isAdmin() && !$_REQUEST['idprofesional'])
        {
            $prf = new Profesional();
            $prfAct = $prf->getActivos();
            $arr['idprofesional_opt'] = '<option value="0">Seleccionar</option>';
        }
        else
        {   
            $idprofesional = $auth->get('idprofesional');
            $prfAct[$idprofesional]['idprofesional'] = $idprofesional;
            $prfAct[$idprofesional]['ayn'] = $auth->get('ayn');
        }
        
        if (!empty($prfAct))
        {
            foreach ($prfAct as $rw)
                $arr['idprofesional_opt'] .= '<option value="'.$rw['idprofesional'].'">'.$rw['ayn'].'</option>';
        }

        $arr['fecha'] = date('d/m/Y');
        $arr['idprofesional'] = date('d/m/Y');

        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('app/pacientes_addLog',$arr);
    }
}
