<?php
include_once LIB_PATH."Controller.php";
include_once LIB_PATH."Html.php";
include_once LIB_PATH."HtmlTableDg.php";
include_once MDL_PATH."usr/UsrUsuario.php";
include_once MDL_PATH."Profesional.php";

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
        $diasPass = $auth->getPasswordVtoDias();
        if ($diasPass < 1)
            $arr['password_vto'] = '<h5 class="text-danger">Su password se encuentra vencido</h5>';
        elseif ($diasPass < 7)
            $arr['password_vto'] = '<span class="text-primary">Su password vence en '.$diasPass.' dia'.($diasPass>1?'s':'').' ('. $auth->get('password_vto').')</span>';
        else
            $arr['password_vto'] = $auth->get('password_vto');
    
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
        $ds = $usr->getDataSet(null,'usuario.ayn');

        $dg = new HtmlTableDg();
        $dg->addHeader('Apellido y Nombre');
        $dg->addHeader('Nombre de usuario');
        $dg->addHeader('Perfil');
        $dg->addHeader('Mail');
        $dg->addHeader('Acciones');

        if (!empty($ds))
        {
            foreach ($ds as $rw)
            {
                /*
                if (!$rw['block'])
                    $blockBtn = '<button onclick="toogleBlock('.$rw['idusuario'].')" id="arBtn" class="btn btn-sm btn-success">
                        <span class="glyphicon glyphicon-ok"></span>
                        </button>';
                else
                    $blockBtn = '<button onclick="toogleBlock('.$rw['idusuario'].')" id="arBtn" class="btn btn-sm btn-danger">
                        <span class="glyphicon glyphicon-ban-circle"></span>
                        </button>';
                */

                $htmlAcciones = '<button onclick="ficha('.$rw['idusuario'].')" id="btnFicha" class="btn btn-sm btn-primary" title="Ver ficha">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        </button>';
                $htmlAcciones .= '&nbsp;<button onclick="editar('.$rw['idusuario'].')" id="btnEditar" class="btn btn-sm btn-secondary" title="Editar datos">
                        <span class="glyphicon glyphicon-pencil"></span>
                        </button>';
                if (!$rw['idprofesional'])
                    $htmlAcciones .= '&nbsp;<button onclick="creaProfesional('.$rw['idusuario'].')" id="btnPrf" class="btn btn-sm btn-success" title="Crear Nuevo Profesional">
                            <span class="glyphicon glyphicon-education"></span>
                            </button>';


                $row = array($rw['ayn'],
                             $rw['username'],
                             ($rw['idprofesional']?'Profesional ':'').UsrUsuario::getTiposDeUsuario($rw['idperfil']),
                             $rw['mail'],
                             $htmlAcciones,
                                );
                $dg->addRow($row);
            }
        }
    
        $arr['data'] = $dg->get();
        $arr['hidden'] = '';
    
        $this->addView('usr/usuarios',$arr);
    }

    function editar($auth)
    {
        $idusuario = $_REQUEST['idusuario'];
        if ($idusuario)
            $this->addTitle('Editar Usuario');
        else
            $this->addTitle('Nuevo Usuario');

        if (!$auth->isAdmin())
        {
             $this->adderror('No esta autorizado a visualizar esta pagina.');
             return null;
        }

        if ($idusuario)
            $usr = new UsrUsuario($idusuario);
        else
            $usr = new UsrUsuario();

        if ($idusuario && $idusuario != $usr->get('idusuario'))
        {
             $this->adderror('Se debe especificar un Id valido');
             return null;
        }

        if (!$idusuario && $_REQUEST['idprofesional'])
        {
            $prf = new Profesional($_REQUEST['idprofesional']);
            $usr->set($prf->getAllData());
        }

        $arr['ayn'] = $usr->get('ayn');
        $arr['mail'] = $usr->get('mail');
        $arr['username'] = $usr->get('username');

        $opt = UsrUsuario::getTiposDeUsuario();
        foreach ($opt as $k=>$v)
            if ($k != UsrUsuario::USUARIO_CNS)
                $arr['idperfil_opt'] .= '<option value="'.$k.'" '.($usr->get('idperfil')==$k?' SELECTED ':'').'>'.$v.'</option>';
        $arr['idusuario'] = $idusuario;
   
        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('usr/editar',$arr);
    }
    
    function ficha($auth)
    {
        $idusuario = $_REQUEST['idusuario'];
        $this->addTitle('Ficha Usuario');

        if (!$auth->isAdmin())
        {
             $this->adderror('No esta autorizado a visualizar esta pagina.');
             return null;
        }

        $usr = new UsrUsuario($idusuario);
        if (!$idusuario || $idusuario != $usr->get('idusuario'))
        {
             $this->adderror('Se debe especificar un Id valido');
             return null;
        }
        
        $this->addTitle($usr->get('ayn'));

        $arr['ayn'] = $usr->get('ayn');
        $arr['mail'] = $usr->get('mail');
        $arr['username'] = $usr->get('username');
        $arr['idusuario'] = $idusuario;

        if (!$usr->get('idprofesional'))
            $arr['profesional'] = '<a class="btn btn-success btn-sm menu-admin" href="app.profesional.editar+idusuario={{idusuario}}"><span class="glyphicon glyphicon-education"></span> Crear Profesional</a>';
        else
            $arr['profesional'] = '<span class="text-primary"><span class="glyphicon glyphicon-ok-circle"></span><span>';
        
        $arr['data'] = '';
        $arr['hidden'] = '';
   
        $this->addView('usr/ficha',$arr);
    }    
}
?>
