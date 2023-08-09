<?php
include_once LIB_PATH."ModelDB.php";
include_once MDL_PATH."Paciente.php";

class Profesional extends ModelDB
{
    protected $query = "SELECT profesional.*,
                               usuario.idusuario,
                               usuario.username,
                               usuario.idperfil 
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

    function asignarPaciente($idpaciente)
    {
        if (!$this->data['idprofesional'])
            CriticalExit('Profesional::asignarPaciente() - Se debe especificar un id valido');
        if (!$idpaciente)
        {
            $err[] = 'Se debe especificar un Paciente';
        }
        else
        {
            $pct = new Paciente($idpaciente);
            if ($pct->get('idpaciente') != $idpaciente)
                $err[] = 'Se debe especificar un Paciente valido';
         
            $pacientes = $this->getPacientesAsignados();
            if (isset($pacientes[$idpaciente]))   
                $err[] = 'El Paciente ya se encuentra asignado';
        }


        if (!empty($err))
        {
            $this->errLog->add($err);
            return false;
        }

        $ins = 'INSERT INTO profesional_paciente (idprofesional,idpaciente) VALUES ('.$this->data['idprofesional'].','.$idpaciente.')';
        $this->db->query($ins);
        return true;

    }

    function desasignarPaciente($idpaciente)
    {
        if (!$this->data['idprofesional'])
            CriticalExit('Profesional::asignarPaciente() - Se debe especificar un id valido');
        if (!$idpaciente)
        {
            $err[] = 'Se debe especificar un Paciente';
        }
        else
        {
            $pct = new Paciente($idpaciente);
            if ($pct->get('idpaciente') != $idpaciente)
                $err[] = 'Se debe especificar un Paciente valido';
         
            $pacientes = $this->getPacientesAsignados();
            if (!isset($pacientes[$idpaciente]))   
                $err[] = 'El Paciente no se encuentra asignado';
        }


        if (!empty($err))
        {
            $this->errLog->add($err);
            return false;
        }

        $ins = 'DELETE FROM profesional_paciente WHERE idprofesional = '.$this->data['idprofesional'].' AND idpaciente='.$idpaciente;
        $this->db->query($ins);
        return true;

    }

    function getPacientesAsignados($idpaciente = null)
    {
        if (!$this->data['idprofesional'])
            CriticalExit('Profesional::getPacientesAsignados() - Se debe especificar un id valido');
        $qry = 'SELECT paciente.* 
                FROM profesional_paciente
                LEFT JOIN paciente ON paciente.idpaciente = profesional_paciente.idpaciente 
                WHERE profesional_paciente.idprofesional = '.$this->data['idprofesional'];
        if ($idpaciente)
            $qry .= ' AND paciente.idpaciente = '.$idpaciente;
        $qry .=' ORDER BY inactivo, paciente.ayn';
        $stmt = $this->db->query($qry);
        $pacientes = array();
        while($rw = $stmt->fetch())
        {
            $pacientes[$rw['idpaciente']] = $rw;
        } 
        return $pacientes;
    }

}