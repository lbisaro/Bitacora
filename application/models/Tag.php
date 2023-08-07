<?php
include_once LIB_PATH."ModelDB.php";

class Tag extends ModelDB
{
    protected $query = "SELECT * FROM tag";

    protected $pKey  = 'idtag';

    const IDTAG_ACTIVO = 1;
    const IDTAG_INACTIVO = 2;

    function __Construct($id=null)
    {
        parent::__Construct();

        //($db,$tabl,$id)
        $this->addTable(DB_NAME,'tag','idtag');

        if ($id)
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

        if (!$this->data['tag'] || strlen($this->data['tag'])<5)
        {
            $err[] = 'Se debe especificar un tag Valido (5 o mas caracteres)';
        }

        if ($this->data['inactivo'])
            $this->data['inactivo'] = 1;
        else
            $this->data['inactivo'] = 0;

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
        if (!$this->data['idtag'])
        {
            $isNew = true;
            $this->data['idtag'] = $this->getNewId();
        }

        if (!$this->valid())
        {
            return false;
        }

        //Grabando datos en las tablas de la db
        if ($isNew) // insert
        {
            if (!$this->tableInsert(DB_NAME,'tag'))
                $err++;
        }
        else       // update
        {
            if (!$this->tableUpdate(DB_NAME,'tag'))
                $err++;
        }
        if ($err)
            return false;
        return true;
    }

    function toogleInactivo()
    {
        if ($this->data['idtag'])
        {
            if ($this->data['inactivo']>0)
                $upd = 'UPDATE tag SET inactivo = 0 WHERE idtag = '.$this->data['idtag'];
            else
                $upd = 'UPDATE tag SET inactivo = 1 WHERE idtag = '.$this->data['idtag'];
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
        return $this->getDataSet("inactivo < 1",'tag');
    }

    function getToAddLog()
    {
        $activos = $this->getActivos();
        $tags = array();
        if (!empty($activos))
            foreach ($activos as $rw)
                if (!$rw['sysid'])
                    $tags[] = $rw;
        return $tags;
    }
}