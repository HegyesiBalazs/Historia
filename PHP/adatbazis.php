<?php
class Adatbazis {
    private $host = "localhost";
    private $felhasznalo = "root";
    private $jelszo = "";
    private $database = "historia";
    private $kapcsolat;

    public function __construct() {
        try {
            $this->kapcsolat = new mysqli(
                $this->host,
                $this->felhasznalo,
                $this->jelszo,
                $this->database
            );

            // Karakterkódolás beállítása
            $this->kapcsolat->set_charset("utf8");

            // Kapcsolódási hiba ellenőrzése
            if ($this->kapcsolat->connect_error) {
                throw new Exception("A kapcsolódás sikertelen: " . $this->kapcsolat->connect_error);
            }
        } catch (Exception $e) {
            die("Kapcsolódási hiba: " . $e->getMessage());
        }
    }

    // Getter a kapcsolat lekérdezéséhez
    public function getKapcsolat() {
        return $this->kapcsolat;
    }

    // Kapcsolat lezárása
    public function __destruct() {
        if ($this->kapcsolat) {
            $this->kapcsolat->close();
        }
    }
}
?>