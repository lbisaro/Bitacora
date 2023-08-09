<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."ControllerAjax.php";
include_once LIB_PATH."HtmlTableDg.php";
include_once MDL_PATH."Evento.php";

/**
 * EventoAjax
 *
 * @package SGi_Controllers
 */
class EventoAjax extends ControllerAjax
{
    function consultar()
    {
        $evnt = new Evento();
        $prm['idpaciente'] = $_REQUEST['idpaciente'];
        if (isset($_REQUEST['idprofesional']))
            $prm['idprofesional'] = $_REQUEST['idprofesional'];
        if (isset($_REQUEST['from']))
            $prm['from'] = $_REQUEST['from'];
        if (isset($_REQUEST['to']))
            $prm['to'] = $_REQUEST['to'];
        $log = $evnt->consultar($prm);

        $dg = new HtmlTableDg('log');
        $dg->addHeader('Fecha',$class=null,$width='15%');
        $dg->addHeader('Paciente',$class=null,$width='15%');
        $dg->addHeader('Notas');
        if (!empty($log))
        {
            foreach ($log as $rw)
            {
                $evento = dateToStr($rw['fecha']);
                if ($rw['profesional_ayn'])
                    $evento .= '<br/><b>'.$rw['profesional_ayn'].'</b>';
                $user_sign = '<small class="text-secondary">'.$rw['username'].' '.dateToStr($rw['datetime'],true).'</small>';
                
                $paciente = $rw['paciente_ayn'];

                $notas = '<b class="'.($rw['sysid']?"text-secondary":"").'">'.$rw['tag'].'</b>';
                if ($rw['notas'])
                    $notas .= '<br/>'.nl2br($rw['notas']);
                $notas .= '<div class="text-right">'.$user_sign.'<div>';
                $dg->addRow(array($evento,$paciente,$notas),$class=null,$height=null,$valign=null,$id=$rw['idpacientelog']);
            }
        }

        $this->ajxRsp->assign('eventos','innerHTML',$dg->get());
        $this->ajxRsp->script('formatTabla()');

    }

    function eliminar()
    {
        $idpacientelog = $_REQUEST['idpacientelog'];
        $evnt = new Evento();
        if ($evnt->delete($idpacientelog))
            $this->ajxRsp->script('regresar()');
        else
            $this->ajxRsp->addError('No fue posible eliminar el registro');
    }
}