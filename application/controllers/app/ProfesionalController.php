<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."Html.php";
include_once LIB_PATH."HtmlTableDg.php";
include_once MDL_PATH."Profesional.php";
include_once MDL_PATH."Paciente.php";

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
                $htmlAcciones = '<button onclick="ficha('.$rw['idprofesional'].')" id="btnFicha" class="btn btn-sm btn-primary" title="Ver ficha">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        </button>';
                $htmlAcciones .= '&nbsp;<button onclick="editar('.$rw['idprofesional'].')" id="btnEditar" class="btn btn-sm btn-secondary" title="Editar">
                        <span class="glyphicon glyphicon-pencil"></span>
                        </button>';
                if (!$rw['username'])
                    $htmlAcciones .= '&nbsp;<button onclick="crearUsuario('.$rw['idprofesional'].')" id="btnUsr" class="btn btn-sm btn-success" title="Crear Usuario">
                            <span class="glyphicon glyphicon-tasks"></span>
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

    function ficha($auth)
    {
        $idprofesional = $_REQUEST['idprofesional'];
        $this->addTitle('Ficha Profesional');

        if (!$auth->isAdmin())
        {
             $this->adderror('No esta autorizado a visualizar esta pagina.');
             return null;
        }

        $prf = new Profesional($idprofesional);
        if (!$idprofesional || $idprofesional != $prf->get('idprofesional'))
        {
             $this->adderror('Se debe especificar un Id valido');
             return null;
        }
        
        $this->addTitle($prf->get('ayn'));

        $arr['ayn'] = $prf->get('ayn');
        $arr['mail'] = $prf->get('mail');
        $arr['telefono'] = $prf->get('telefono');
        $arr['cargo'] = $prf->get('cargo');
        $arr['idprofesional'] = $idprofesional;

        if (!$prf->get('username'))
            $arr['usuario'] = '<a class="btn btn-success btn-sm menu-admin" href="usr.usr.editar+idprofesional={{idprofesional}}"><span class="glyphicon glyphicon-tasks"></span> Crear Usuario</a>';
        else
            $arr['usuario'] = '<span class="text-primary"><span class="glyphicon glyphicon-ok-circle"></span> '.$prf->get('username').'<span>';

        $pacientesAsignados = $prf->getPacientesAsignados();
        $pct = new Paciente();
        $pacientesDisponibles = $pct->getActivos();
        foreach ($pacientesDisponibles as $idpaciente => $rw)
        {
            if (isset($pacientesAsignados[$idpaciente]))
                unset($pacientesDisponibles[$idpaciente]);
        }
        foreach ($pacientesAsignados as $idpaciente => $rw)
        {
            $strInactivo = '';
            if ($rw['inactivo'])
                $strInactivo = ' <i class="text-danger small">(Inactivo)</i>';
            $arr['pacientes_asignados'] .= '<div class="paciente_asignado" id="pa_'.$idpaciente.'">
                                            <span id="paayn_'.$idpaciente.'" width="90%">'.$rw['ayn'].'</span>
                                            '.$strInactivo.'
                                            <button class="btn btn-sm" onclick="delPaciente('.$idpaciente.')"><span class="glyphicon glyphicon-remove-sign text-danger"></span>
                                            </div>';
        }
        if (!empty($pacientesDisponibles))
        {
            $arr['pacientes_asignados'] .= '<select id="add_paciente" class="form-control form-control-sm" onchange="addPaciente()">';
            $arr['pacientes_asignados'] .= '<option value="0">Asignar nuevo paciente</option>';
            foreach ($pacientesDisponibles as $idpaciente => $rw)
                $arr['pacientes_asignados'] .= '<option value="'.$idpaciente.'">'.$rw['ayn'].'</option>';
            $arr['pacientes_asignados'] .= '</select>';
        }

        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('app/profesionales_ficha',$arr);
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
        }
        else
        {
            $prf = new Profesional();
        }
        if (!$idprofesional && $idusuario = $_REQUEST['idusuario'])
        {
            $usr = new UsrUsuario($idusuario);
            $prf->set($usr->getAllData());
        }
        $arr['ayn'] = $prf->get('ayn');
        $arr['mail'] = $prf->get('mail');
        $arr['telefono'] = $prf->get('telefono');
        $arr['cargo'] = $prf->get('cargo');
        $arr['idprofesional'] = $idprofesional;
        
        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('app/profesionales_editar',$arr);
    }

}
