<?php
include_once LIB_PATH."DB.php";
include_once LIB_PATH."ErrorLog.php";
include_once LIB_PATH."functions.php";
include_once LIB_PATH."Html.php";
include_once MDL_PATH."Profesional.php";
include_once MDL_PATH."Paciente.php";



class Evento 
{
    protected $db;

    protected $fields = array();
    protected $data   = array();
    protected $foundRows;

    protected $errLog;

    protected $qryBase = "SELECT paciente_log.*,
                                 tag.tag,
                                 usuario.username,
                                 paciente.ayn as paciente_ayn, 
                                 profesional.ayn as profesional_ayn
                          FROM paciente_log
                          LEFT JOIN tag ON tag.idtag = paciente_log.idtag
                          LEFT JOIN paciente ON paciente_log.idpaciente = paciente.idpaciente
                          LEFT JOIN profesional ON paciente_log.idprofesional = profesional.idprofesional
                          LEFT JOIN usuario ON paciente_log.idusuario = usuario.idusuario";

    public function __Construct()
    {
        $this->db = DB::getInstance();
        $this->errLog = new ErrorLog();
    }

    function get($idpacientelog)
    {
        if (!$idpacientelog)
            return null;
        
        $qry = $this->qryBase." WHERE idpacientelog = '".$idpacientelog."' ";
        $stmt = $this->db->query($qry);
        return $stmt->fetch(); 
    }

    function consultar($arr=array())
    {

        $auth = UsrUsuario::getAuthInstance();
        $idprofesional = $auth->get('idprofesional');
        $pacientesAsignados = array();


        $qryBase = $this->qryBase;
        
        $qryOrder = " ORDER BY datetime DESC";
        $qryWhere = '';

        if ($idprofesional)
        {
            $prf = new Profesional($idprofesional);
            $pacientesAsignados = $prf->getPacientesAsignados();
        }

        if ($arr['idpaciente']=='all')
        {
            if ($auth->isAdmin())
            {
                $qryWhere .= " AND paciente_log.idpaciente > 0";
            }
            else
            {
                if (!empty($pacientesAsignados))
                {
                    $whereIn = '';
                    foreach ($pacientesAsignados as $rw)
                    {
                        $whereIn .= ($arr['idpaciente']?',':'').$rw['idpaciente'];
                    }
                    $qryWhere .= " AND paciente_log.idpaciente in (".$whereIn.")";
                }
            }
        }
        elseif ($arr['idpaciente']=='available')
        {
            if (!empty($pacientesAsignados))
            {
                $whereIn = '';
                foreach ($pacientesAsignados as $rw)
                {
                    $whereIn .= ($arr['idpaciente']?',':'').$rw['idpaciente'];
                }
                $qryWhere .= " AND paciente_log.idpaciente in (".$whereIn.")";
            }
        }
        elseif ($arr['idpaciente']>0)
        {
            if ($auth->isAdmin())
            {
                $qryWhere = " AND paciente_log.idpaciente = '".$arr['idpaciente']."'";
            }
            else
            {
                if (!empty($pacientesAsignados))
                {
                    foreach ($pacientesAsignados as $rw)
                    {
                        if ($rw['idpaciente'] == $arr['idpaciente'])
                            $qryWhere = " AND paciente_log.idpaciente = '".$arr['idpaciente']."'";
                    }
                }
            }
        }
                    

        if ($arr['idprofesional']>0)
            if ($auth->isAdmin())
                $qryWhere .= " AND paciente_log.idprofesional = ".$arr['idprofesional']." ";

        if ($auth->isAdmin() || $qryWhere)
        {
            if ($arr['from'])
                $qryWhere .= " AND (datetime >= '".strToDate($arr['from'],true)."' OR fecha >= '".strToDate($arr['from'])."')";
            if (isset($arr['to']))
                $qryWhere .= " AND (datetime <= '".strToDate($arr['to'],true)."' OR fecha <= '".strToDate($arr['to'])."')";
        }
            
        if ($qryWhere)
        {
            $qry = $qryBase.' WHERE 1 '.$qryWhere.$qryOrder;
            $stmt = $this->db->query($qry);
            return $stmt->fetchAll();   
        }
    }
}