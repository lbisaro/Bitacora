<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."Html.php";
include_once LIB_PATH."HtmlTableDg.php";
include_once MDL_PATH."usr/UsrUsuario.php";

/**
 * UsrController
 *
 * @package SGi_Controllers
 */
class UsrController extends Controller
{

    function login($auth)
    {
        $this->addTitle(SOFTWARE_NAME.' - Abrir sesion');

        $this->setFocus('login_username');

        $arr['titulo'] = 'Abrir sesión de sistema';

        $arr['SOFTWARE_NAME']   = SOFTWARE_NAME;
        $arr['SOFTWARE_VER']   = SOFTWARE_VER;
        $arr['input_username']   = Html::getTagInput('login_username',null,null,array('AUTOCOMPLETE'=>'OFF'));
        $arr['input_password']   = Html::getTagInput('login_password',null,'password');

        $this->addView('usr\login',$arr);
    }

    function logout($auth)
    {
        session_destroy();
        UsrUsuario::killAuthInstance();
        setcookie('UID');
        header ("Location: .");
        exit;
    }


    function perfil($auth)
    {
        $this->addTitle('Cuenta');

        $arr['idusuario'] = $auth->get('idusuario');
        $arr['ayn'] = $auth->get('ayn');
        $arr['username'] = $auth->get('username');
        $arr['mail'] = $auth->get('mail');
    
        $this->addView('usr/perfil',$arr);
    }


    function usuarios($auth)
    {
        $this->addTitle('Usuarios');
    
        if (!$auth->isAdmin())
        {
            $this->addError('No esta autorizado a visualizar esta pagina.');
            return null;
        }
    
        $usr = new UsrUsuario();
        $ds = $usr->getDataSet(null,'ayn');

        $dg = new HtmlTableDg();
        $dg->addHeader('Apellido y Nombre');
        $dg->addHeader('Nombre de usuario');
        $dg->addHeader('Perfil');
        $dg->addHeader('Mail');
        $dg->addHeader('Usuario Activo');

        if (!empty($ds))
        {
            foreach ($ds as $rw)
            {
                if (!$rw['block'])
                    $blockBtn = '<button onclick="toogleBlock('.$rw['idusuario'].')" id="arBtn" class="btn btn-sm btn-success">
                        <span class="glyphicon glyphicon-ok"></span>
                        </button>';
                else
                    $blockBtn = '<button onclick="toogleBlock('.$rw['idusuario'].')" id="arBtn" class="btn btn-sm btn-danger">
                        <span class="glyphicon glyphicon-ban-circle"></span>
                        </button>';
                $blockBtn = 
                $row = array($rw['ayn'],
                             $rw['username'],
                             UsrUsuario::getTiposDeUsuario($rw['idperfil']),
                             $rw['mail'],
                             $blockBtn,
                                );
                $dg->addRow($row);
            }
        }
    
        $arr['data'] = $dg->get();
        $arr['hidden'] = '';
    
        $this->addView('usr/usuarios',$arr);
    }
    
    
}
?>
