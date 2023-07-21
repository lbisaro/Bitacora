<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."ControllerAjax.php";
include_once MDL_PATH."Profesional.php";
include_once MDL_PATH."usr/UsrUsuario.php";

/**
 * ProfesionalAjax
 *
 * @package SGi_Controllers
 */
class ProfesionalAjax extends ControllerAjax
{
    function grabar()
    {
        if ($_REQUEST['idprofesional'])
            $prf = new Profesional($_REQUEST['idprofesional']);
        else
            $prf = new Profesional();

        $arrToSet['ayn'] = $_REQUEST['ayn'];
        $arrToSet['mail'] = $_REQUEST['mail'];
        $arrToSet['telefono'] = $_REQUEST['telefono'];
        $arrToSet['cargo'] = $_REQUEST['cargo'];
        $prf->set($arrToSet);
        if ($prf->save())
        {
            $idusuario = $prf->get('idusuario');
            if ($idusuario)
            {
                $usr = new UsrUsuario($idusuario);
                $usr->set($arrToSet);
                $usr->save();
            }
            $this->ajxRsp->redirect(Controller::getLink('app','profesional','listar'));
        }
        else
        {
            $this->ajxRsp->addError($prf->getErrLog());        
        }
    }
    
    function toogleInactivo()
    {
        $prf = new Profesional($_REQUEST['idprofesional']);
        $prf->toogleInactivo();
        $this->ajxRsp->redirect(Controller::getLink('app','profesional','listar'));        
    }

    function addPaciente()
    {
        $idprofesional = $_REQUEST['idprofesional'];
        $idpaciente = $_REQUEST['idpaciente'];
        $prf = new Profesional($idprofesional);
        if ($prf->asignarPaciente($idpaciente))
            $this->ajxRsp->redirect(Controller::getLink('app','profesional','ficha','idprofesional='.$idprofesional));
        else
            $this->ajxRsp->addError($prf->getErrLog()); 
    }

    function delPaciente()
    {
        $idprofesional = $_REQUEST['idprofesional'];
        $idpaciente = $_REQUEST['idpaciente'];
        $prf = new Profesional($idprofesional);
        if ($prf->desasignarPaciente($idpaciente))
            $this->ajxRsp->redirect(Controller::getLink('app','profesional','ficha','idprofesional='.$idprofesional));
        else
            $this->ajxRsp->addError($prf->getErrLog()); 
    }
}