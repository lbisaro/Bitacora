<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."ControllerAjax.php";
include_once MDL_PATH."Paciente.php";

/**
 * PacienteAjax
 *
 * @package SGi_Controllers
 */
class PacienteAjax extends ControllerAjax
{
    function grabar()
    {
        if ($_REQUEST['idpaciente'])
            $pct = new Paciente($_REQUEST['idpaciente']);
        else
            $pct = new Paciente();

        $arrToSet['ayn'] = $_REQUEST['ayn'];
        $arrToSet['mail'] = $_REQUEST['mail'];
        $arrToSet['telefono'] = $_REQUEST['telefono'];
        $pct->set($arrToSet);
        if ($pct->save())
            $this->ajxRsp->redirect(Controller::getLink('app','paciente','listar'));
        else
            $this->ajxRsp->addError($pct->getErrLog());        
    }
    
    function toogleInactivo()
    {
        $pct = new Paciente($_REQUEST['idpaciente']);
        $pct->toogleInactivo();
        $this->ajxRsp->redirect(Controller::getLink('app','paciente','listar'));        
    }

    function addLog()
    {
        $idpaciente = $_REQUEST['idpaciente'];
        $pct = new Paciente($idpaciente);
        if ($pct->addLog($_REQUEST['idtag'],$_REQUEST['idprofesional'],$_REQUEST['notas'],strToDate($_REQUEST['fecha'])))
            $this->ajxRsp->redirect(Controller::getLink('app','paciente','ficha','idpaciente='.$idpaciente));
        else
            $this->ajxRsp->addError($pct->getErrLog());        
    }

}