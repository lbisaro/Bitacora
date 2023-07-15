<?php
include_once LIB_PATH."ModelDB.php";
include_once MDL_PATH."Tag.php";
include_once MDL_PATH."Profesional.php";

class Paciente extends ModelDB
{
    protected $query = "SELECT * FROM paciente";

    protected $pKey  = 'idpaciente';

    function __Construct($id=null)
    {
        parent::__Construct();

        //($db,$tabl,$id)
        $this->addTable(DB_NAME,'paciente','idpaciente');

        if($id)
            $this->load($id);
    }

    function get($field)
    {
        return parent::get($field);
    }

    function getLabel($field)
    {
        return parent::getLabel($field);
    }

    function getInput($field,$value=null)
    {
        if (!$value)
            $value = $this->data[$field];

        return parent::getInput($field);
    }

    function validReglasNegocio()
    {
        $err=null;

        // Control de errores

        if (!$this->data['ayn'])
            $err[] = 'Se debe especificar Apellido y Nombre';
        if (!$this->data['mail'])
            $err[] = 'Se debe especificar Mail';
        if (!$this->data['telefono'])
            $err[] = 'Se debe especificar Telefono';


        // FIN - Control de errores

        if (!empty($err))
        {
            $this->errLog->add($err);
            return false;
        }
        return true;
    }

    function save()
    {
        $err   = 0;
        $isNew = false;

        // Creando el Id en caso que no este
        if (!$this->data['idpaciente'])
        {
            $isNew = true;
            $this->data['idpaciente'] = $this->getNewId();
            $this->data['fecha_alta'] = date('Y-m-d');
        }

        if (!$this->valid())
        {
            return false;
        }

        //Grabando datos en las tablas de la db
        if ($isNew) // insert
        {
            if (!$this->tableInsert(DB_NAME,'paciente'))
                $err++;
            $this->addLog(Tag::IDTAG_ALTA,0,'',date('Y-m-d'));
        }
        else       // update
        {
            if (!$this->tableUpdate(DB_NAME,'paciente'))
                $err++;
        }
        if ($err)
            return false;
        return true;
    }

    function toogleInactivo()
    {
        if ($this->data['idpaciente'])
        {
            if ($this->data['inactivo']>0)
                $upd = 'UPDATE paciente SET inactivo = 0 WHERE idpaciente = '.$this->data['idpaciente'];
            else
                $upd = 'UPDATE paciente SET inactivo = 1 WHERE idpaciente = '.$this->data['idpaciente'];
            $this->db->query($upd);
            return true;
        }
        return false;
    }

    function addLog($idtag,$idprofesional,$notas,$fecha)
    {
        $auth = UsrUsuario::getAuthInstance();
        $idusuario = $auth->get('idusuario');
        $idpaciente = $this->data['idpaciente'];

        $tag = new Tag($idtag);
        if (!$idtag || $idtag != $tag->get('idtag'))
            $this->errLog->add('Se deben especificar un Tag valido');
        elseif (!$tag->isActivo())
            $this->errLog->add('No es posible registrar un evento con un Tag Inactivo');

        if (empty($tag->get('sysid')))
        {
            $prf = new Profesional($idprofesional);
            if (!$idprofesional || $idprofesional != $prf->get('idprofesional'))
                $this->errLog->add('Se deben especificar un Profesional valido');
            elseif (!$prf->isActivo())
                $this->errLog->add('No es posible registrar un evento con un Profesional Inactivo');
           

            if (!$idpaciente)
                CriticalExit('Paciente::addLog() - No se especifico un idpaciente valido');
            if (!$idusuario)
                CriticalExit('Paciente::addLog() - No se especifico un idusuario valido');
            if (!$notas)
                $this->errLog->add('Se deben especificar Notas');
            
        }

        if (!empty($this->getErrLog($reset=false)))
            return false;

        $ins = "INSERT INTO paciente_log (idtag,idpaciente,idprofesional,notas,fecha,datetime,idusuario) VALUES (".
                "'".$idtag."',".
                "'".$idpaciente."',".
                "'".$idprofesional."',".
                "'".trim($notas)."',".
                "'".$fecha."',".
                "'".date('Y-m-d H:i:s')."',".
                "'".$idusuario."' ".
                ")";
        $this->db->query($ins);
        return true;
    }

    function isActivo()
    {
        return (empty($this->data['inactivo']));
    }

    function getLog()
    {
        $qry = "SELECT paciente_log.*,
                       tag.tag,
                       profesional.ayn as profesional_ayn,
                       usuario.username
                FROM paciente_log
                LEFT JOIN tag ON tag.idtag = paciente_log.idtag
                LEFT JOIN profesional ON profesional.idprofesional = paciente_log.idprofesional
                LEFT JOIN usuario ON usuario.idusuario = paciente_log.idusuario
                WHERE paciente_log.idpaciente = ".$this->data['idpaciente']."
                ORDER BY datetime DESC";
        $stmt = $this->db->query($qry);
        $ds = $stmt->fetchAll();
        return $ds;

    }
}