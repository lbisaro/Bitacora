<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."ControllerAjax.php";
include_once MDL_PATH."usr/UsrUsuario.php";
include_once MDL_PATH."Profesional.php";

class UsrAjax extends ControllerAjax
{
    function login()
    {
        $user = $_REQUEST['login_username'];
        $pass = $_REQUEST['login_password'];
        setcookie('UID');
        if (!$user || !$pass)
        {
            if (!$user)
        	{
        		$error = 'Debe especificar el nombre de usuario';
        	}
        	if (!$pass)
        	{
        		$error = ($error?$error.' y password':'Debe especificar el password');
        	}
        }
    	elseif (!UsrUsuario::validAuth($user,$pass))
    	{
    		$error = 'No coincide usuario y/o password';
    	}
        elseif (!UsrUsuario::setAuthInstance($user,$pass))
        {
            $error = 'No se pudo instanciar la sesion de usuario';
        }

        if ($auth = UsrUsuario::getAuthInstance())
        {
            if ($auth->get('idperfil') < UsrUsuario::PERFIL_CNS)
                $error = 'La cuenta de usuario se encuentra inhabilitada.';
        }
        if ($error)
        {
            $this->ajxRsp->script("$('#login_msg').removeClass('text-success')");
            $this->ajxRsp->script("$('#login_msg').addClass('text-danger')");
        	$this->ajxRsp->script("$('#login_msg').html('".$error."')");

        }
        else
        {
            $auth->registrarAcceso();
            setcookie('UID',$auth->get('idusuario'));
            if (isset($_SESSION['cachedRequest']) && empty($_SESSION['cachedRequest']['post']))
            {
                $mod  = $_SESSION['cachedRequest']['moduleName'];
                $ctrl = $_SESSION['cachedRequest']['controllerName'];
                $act  = $_SESSION['cachedRequest']['actionName'];
                $prm='';
                if (!empty($_SESSION['cachedRequest']['get']))
                    foreach ($_SESSION['cachedRequest']['get'] as $k => $v)
                    {
                        if (!in_array($k, array('mod','ctrl','act')))
                            $prm  .= ($prm?'&':'').$k.'='.$v;
                    }

                unset($_SESSION['cachedRequest']);
            }
            else
            {
                $mod  = 'app';
                $ctrl = 'mst';
                $act  = 'home';
                $prm  = 'login=OK';
            }
            $this->ajxRsp->redirect(Controller::getLink($mod,$ctrl,$act,$prm));
            $this->ajxRsp->script("$('#login_msg').removeClass('text-danger')");
            $this->ajxRsp->script("$('#login_msg').addClass('text-success')");
            $this->ajxRsp->script("$('#login_msg').html('Acceso correcto!')");
        }
    }

    /**
    * Funcion ajax para modificar password
    */
    function grabarPassword()
    {
        $arrDatos = $_REQUEST;
        $usr = new UsrUsuario($arrDatos['idusuario']);

        if(!$usr->setNewPassword($arrDatos['password'],$arrDatos['oldpassword']))
        {
            $aErr = $usr->getErrLog();
            $htmlError = '';
            if (!empty($aErr))
            {
                foreach($aErr as $err)
                    $htmlError .= '<p>'.$err.'</p>';
            }
            else
            {
                $htmlError = 'El password no pudo ser establecido.';
            }
            $this->ajxRsp->assign('message-error','innerHTML',$htmlError);
            $this->ajxRsp->script("$('#message-error').show();");
        }
        else
        {
            UsrUsuario::deleteAuthInstance();
            $this->ajxRsp->alert('El password se a modificado con exito!! Sera redireccionado al Login. Recuerde que su nuevo password es: "'.$arrDatos['password'].'"');
            $this->ajxRsp->redirect(Controller::getLink('Usr','Usr','login'));
        }
    }

    function grabar()
    {
        $arrDatos = $_REQUEST;
        $usr = new UsrUsuario($arrDatos['idusuario']);
        $arrToSet['ayn'] = $arrDatos['ayn']; 
        $arrToSet['username'] = $arrDatos['username']; 
        $arrToSet['idperfil'] = $arrDatos['idperfil']; 
        $arrToSet['mail'] = $arrDatos['mail']; 

        $usr->set($arrToSet);
        if($usr->save())
        {
            $idprofesional = $usr->get('idprofesional');
            if ($idprofesional)
            {
                $prf = new Profesional($usr->get('idprofesional'));
                $prf->set($arrToSet);
                $prf->save();
            }
            if (!$_REQUEST['idusuario'])
            {
                $usr->resetPassword();
                $this->ajxRsp->alert('El password asignado a la cuenta es '.UsrUsuario::PASS_DEFAULT);
            }
            $this->ajxRsp->redirect(Controller::getLink('usr','usr','usuarios'));

        }
        else
        {
            $this->ajxRsp->addError($usr->getErrLog()); 
        }
    }


    function toogleBlock()
    {
        $usr = new UsrUsuario($_REQUEST['idusuario']);
        $usr->toogleBlock();
        $this->ajxRsp->redirect(Controller::getLink('Usr','Usr','usuarios'));
        
    }

    function resetPassword()
    {
        $usr = new UsrUsuario($_REQUEST['idusuario']);
        $usr->resetPassword();
        $this->ajxRsp->alert('El nuevo password asignado a la cuenta es '.UsrUsuario::PASS_DEFAULT);
        $this->ajxRsp->redirect(Controller::getLink('Usr','Usr','ficha','idusuario='.$_REQUEST['idusuario']));
        
    }

    function setConfig()
    {
        $set = $_REQUEST['set'];
        $str = $_REQUEST['str'];
        $this->ajxRsp->setEchoOut(false);
        $auth = UsrUsuario::getAuthInstance();
        $auth->setConfig($set, $str);
    }

}
?>
