<?php

class Profile_Model extends DPload
{

    public $accountID;
    public $name;
    public $email;
    public $telNr;
    public $faxNr;
    public $isActive;
    public $regDate;
    public $userGroup;
    public $faculty;
    public $userType;

    function __construct()
    {
        parent::__construct();
    }

    public function initProfil()
    {
        Session::init();
        $this->accountID = Session::get('accountId');

        Session::set('IsEmployee', false);
        Session::set('IsStudent', false);


        //TODO: Time Lib
        $timestamp = time();
        $date = date("d.m.Y", $timestamp);
        $time = date("H:i", $timestamp);

        //check if it`s employee or student
        if (!empty($this->accountID))
        {

            $query = "SELECT m.persNr, m.akadTitel, m.vorname, m.nachname, m.telNr, m.faxNr, m.email, m.account_id,
                         a.benutzername, a.aktiv, a.registriert, 
                         g.bezeichnung AS rolle, 
                         fak.bezeichnung AS fakultaet
                  FROM mitarbeiter AS m
                  JOIN account AS a ON account_id = a.id
                  JOIN gruppen AS g ON gruppen_id = g.id
                  JOIN fakultaet AS fak ON fakultaet_id = fak.id
                  WHERE m.persNr = (SELECT persNr FROM mitarbeiter WHERE account_id = " . $this->accountID . ")";

            $rows = $this->db->select($query);
            //array mit index 0 zuordnen
            $row = $rows[0];

            if ($this->db->numRows != 0)
            {
                $this->userType = 'employee';
                Session::set('isEmployee', true);

                $this->name = $row['akadTitel'] . ' ' . $row['vorname'] . ' ' . $row['nachname'];
                $this->email = $row['email'];
                $this->telNr = $row['telNr'];
                $this->faxNr = $row['faxNr'];
                $this->username = $row['benutzername'];
                $this->isActiv = $row['aktiv'];
                $this->regDate = $row['registriert'];
                $this->userGroup = $row['rolle'];
                $this->faculty = $row['fakultaet'];
            }
            else
            {
                $query = "SELECT matrikelNr, vorname, nachname, email, g.bezeichnung AS rolle, a.benutzername AS benutzername
                        FROM studierende
                        JOIN account AS a ON account_id = a.id
                        JOIN gruppen AS g ON gruppen_id = g.id
                        WHERE account_id = (SELECT matrikelNr FROM studierende WHERE account_id = " . $this->accountID . ")";

                $rows = $this->db->select($query);
                $row = $rows[0];

                if ($this->db->numRows != 0)
                {
                    $this->userType = 'student';
                    Session::set('isStudent', true);

                    $this->studentID = $row['matrikelNr'];
                    $this->name = $row['vorname'] . ' ' . $row['nachname'];
                    $this->email = $row['email'];
                    $this->userGroup = $row['rolle'];
                    $this->username = $row['benutzername'];
                }
            }
        }
    }

//----------------------------------------------------------------------------------------------------------------------

    public function getUserDetails()
    {
        
    }

}