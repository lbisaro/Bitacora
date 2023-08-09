<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."Html.php";
include_once MDL_PATH."Evento.php";
include_once MDL_PATH."Profesional.php";
include_once MDL_PATH."Paciente.php";

/**
 * Controller: EventoController
 * @package SGi_Controllers
 */
class EventoController extends Controller
{
    function home($auth)
    {
        $this->addTitle('Registro de eventos');
        $idpaciente = $_REQUEST['idpaciente'];
        $evnt = new Evento();

        $pct = new Paciente();
        $prf = new Profesional();

        $pacientes = array();

        if ($auth->isAdmin())
        {
            $pacientesAll = $pct->getDataSet(null,'inactivo,ayn');
            $profesionales = $prf->getDataSet(null,'inactivo,ayn');
            foreach ($pacientesAll as $rw)
            {
                $pacientes[$rw['idpaciente']]['idpaciente'] = $rw['idpaciente'];
                $pacientes[$rw['idpaciente']]['ayn'] = $rw['ayn'];
            }
        }
        
        $idprofesional = $auth->get('idprofesional');
        if ($idprofesional)
        {
            $prf = new Profesional($idprofesional);
            $pacientesAsignados = $prf->getPacientesAsignados();
            if (!empty($pacientesAsignados))
                foreach ($pacientesAsignados as $rw)
                {
                    $pacientes[$rw['idpaciente']]['idpaciente'] = $rw['idpaciente'];
                    $pacientes[$rw['idpaciente']]['ayn'] = $rw['ayn'];
                }
        }

        

        if ($auth->isAdmin())
            $arr['idpaciente_opt'] .= '<option value="all">Todos</option>';
            
        if (!empty($pacientesAsignados)) 
            $arr['idpaciente_opt'] .= '<option value="asignados">Pacientes Asignados</option>';
        
        if (isset($pacientes))
        {
            foreach ($pacientes as $rw)
            {
                $selected = '';
                if ($idpaciente && $rw['idpaciente'] == $idpaciente)
                    $selected = ' SELECTED ';
                $arr['idpaciente_opt'] .= '<option '.$selected.' value="'.$rw['idpaciente'].'">'.$rw['ayn'].'</option>';
            }
        }

        if ($auth->isAdmin() && !empty($profesionales))
        {

            $arr['html_select_profesional'] = '<div class="row-sm">
                <div class="form-group">
                    <label for="idprofesional">Profesional</label>
                    <div class="input-group mb-2">
                        <select class="form-control" id="idprofesional"  onchange="filtrar()">
                            <option value="0">Todos</option>';
                          
            foreach ($profesionales as $rw)
                $arr['html_select_profesional'] .= '
                <option value="'.$rw['idprofesional'].'">'.$rw['ayn'].'</option>';

            $arr['html_select_profesional'] .= '
                        </select>
                    </div>
                </div>
                
            </div>  ';
        }

        if (!$idpaciente)
        {
            $arr['from'] = date('d/m/Y',strtotime('-10 days'));
            
        }
        $arr['to'] = date('d/m/Y');
        
   
        $this->addView('app/eventos',$arr);
    }


    function ver($auth)
    {
        $this->addTitle('Evento');
    
        $idpacientelog = $_REQUEST['id'];
        $returnTo = $_REQUEST['returnTo'];

        $evnt = new Evento();
        $ds = $evnt->get($idpacientelog);

        if (!$idpacientelog || $idpacientelog != $ds['idpacientelog'])
        {
             $this->adderror('Se debe especificar un Id valido');
             return null;
        }        

        if (empty($ds['sysid']))
        {
            if ($auth->isAdmin() || $auth->get('idusuario') == $ds['idusuario'] )
            {
                //$arr['addButtons'] .= ' <button class="btn btn-primary btn-sm" onclick="editar()">Editar</button>';
                $arr['addButtons'] .= ' <button class="btn btn-danger btn-sm" onclick="eliminar()">Eliminar</button>';
            }
        }

        if ($returnTo)
            $arr['addButtons'] .= ' <button class="btn btn-secondary btn-sm" onclick="regresar()">Volver</button>';
      

        $idpaciente = $ds['idpaciente'];
        $idprofesional = $ds['idprofesional'];

        if (!$auth->checkPaciente($idpaciente))
        {
            $this->adderror('No esta autorizado a visualizar esta pagina.');
            return null;
        }

        $arr['tag'] = $ds['tag'];
        $arr['paciente_ayn'] = $ds['paciente_ayn'];
        $arr['profesional_ayn'] = ($ds['profesional_ayn']?$ds['profesional_ayn']:'Sin especificar');
        $arr['username'] = $ds['username'];
        $arr['datetime'] = dateToStr($ds['datetime'],true);
        $arr['fecha'] = dateToStr($ds['fecha']);
        $arr['notas'] = nl2br($ds['notas']);
    
        $arr['data'] = '';
        $arr['hidden'] = Html::getTagInput('returnTo',$returnTo,'hidden');
        $arr['hidden'] .= Html::getTagInput('idpacientelog',$idpacientelog,'hidden');
        $arr['hidden'] .= Html::getTagInput('idpaciente',$idpaciente,'hidden');
        
    
        $this->addView('app/evento_ver',$arr);
    }
    
}
