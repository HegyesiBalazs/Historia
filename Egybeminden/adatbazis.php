<?php
class Adatbazis {
    private $host = "localhost";
    private $felhasznalo = "root";
    private $jelszo = "";
    private $database = "historia";

    public $kapcs_reg;
    public $kapcs_jelszo;
    public $kapcs_jegyzet;

    public function __construct() {
        try {
            $this->kapcs_reg = new mysqli(
                $this->host, 
                $this->felhasznalo, 
                $this->jelszo, 
                $this->database
            );
            $this->kapcs_reg->set_charset("utf8");

            $this->kapcs_jelszo = new mysqli(
                $this->host, 
                $this->felhasznalo, 
                $this->jelszo, 
                $this->database
            );
            $this->kapcs_jelszo->set_charset("utf8");

            $this->kapcs_jegyzet = new mysqli(
                $this->host, 
                $this->felhasznalo, 
                $this->jelszo, 
                $this->database
            );
            $this->kapcs_jegyzet->set_charset("utf8");

            // Kapcsolódási hibák ellenőrzése
            if ($this->kapcs_reg->connect_error || 
                $this->kapcs_jelszo->connect_error || 
                $this->kapcs_jegyzet->connect_error) {
                throw new Exception("A kapcsolódás sikertelen");
            }
        } catch (Exception $e) {
            die("Kapcsolódási hiba: " . $e->getMessage());
        }
    }
}
?>