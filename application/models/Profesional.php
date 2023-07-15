<?php
include_once LIB_PATH."ModelDB.php";

class Profesional extends ModelDB
{
    protected $query = "SELECT profesional.*,
                               usuario.idusuario,
                               usuario.username  
                        FROM profesional
                        LEFT JOIN usuario ON usuario.mail = profesional.mail";

    protected $pKey  = 'idprofesional';

    function __Construct($id=null)
    {
        parent::__Construct();

        //($db,$tabl,$id)
        $this->addTable(DB_NAME,'profesional','idprofesional');

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
        $idprofesional = $this->data['idprofesional'];

        if (!$this->data['ayn'])
        {
            $err[] = 'Se debe especificar Apellido y Nombre';
        }
        if (!$this->data['mail'])
        {
            $err[] = 'Se debe especificar Mail';
        }
        if ($this->data['mail'] && !validarEmail($this->data['mail']))
        {
            $err[] = 'Se debe especificar Mail con formato valido';
        }
        // Validando que el mail no se encuentre previamente registrdo para otro usuario
        if ($this->data['mail'] && $this->getDataSet("upper(profesional.mail) = '".$this->data['mail']."' ".($idprofesional?" AND idprofesional <> ".$idprofesional:"")))
        {
            $err[] = 'El mail ya se encuentra registrado para otro profesional';
        }
        if (!$this->data['telefono'])
        {
            $err[] = 'Se debe especificar Telefono';
        }
        if (!$this->data['cargo'])
        {
            $err[] = 'Se debe especificar Cargo';
        }


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
        if (!$this->data['idprofesional'])
        {
            $isNew = true;
            $this->data['idprofesional'] = $this->getNewId();
        }

        if (!$this->valid())
        {
            return false;
        }

        //Grabando datos en las tablas de la db
        if ($isNew) // insert
        {
            if (!$this->tableInsert(DB_NAME,'profesional'))
                $err++;
        }
        else       // update
        {
            if (!$this->tableUpdate(DB_NAME,'profesional'))
                $err++;
        }
        if ($err)
            return false;
        return true;
    }

    function toogleInactivo()
    {
        if ($this->data['idprofesional'])
        {
            if ($this->data['inactivo']>0)
                $upd = 'UPDATE profesional SET inactivo = 0 WHERE idprofesional = '.$this->data['idprofesional'];
            else
                $upd = 'UPDATE profesional SET inactivo = 1 WHERE idprofesional = '.$this->data['idprofesional'];
            $this->db->query($upd);
            return true;
        }
        return false;
    }

    function isActivo()
    {
        return (empty($this->data['inactivo']));
    }

    function getActivos()
    {
        return $this->getDataSet('inactivo < 1','ayn');

    }
}