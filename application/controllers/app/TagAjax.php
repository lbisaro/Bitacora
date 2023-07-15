<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."ControllerAjax.php";
include_once MDL_PATH."Tag.php";

/**
 * TagAjax
 *
 * @package SGi_Controllers
 */
class TagAjax extends ControllerAjax
{
    function crear()
    {
        $tag = new Tag();
        $arrToSet['tag'] = $_REQUEST['new_tag'];
        $tag->set($arrToSet);
        if ($tag->save())
            $this->ajxRsp->redirect(Controller::getLink('app','Mst','tags'));
        else
            $this->ajxRsp->addError($tag->getErrLog());        
    }
    
    function toogleInactivo()
    {
        $tag = new Tag($_REQUEST['idtag']);
        $tag->toogleInactivo();
        $this->ajxRsp->redirect(Controller::getLink('app','Mst','tags'));        
    }
}