<?php
include '../configurador.php';

class baseModel{

  protected $bd;

  public function baseModel(){

  
    try {
        $this->bd = new PDO("mysql:host=localhost;dbname=prueba_tecnica_dev",'root', '');      
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
        return   $this->bd;
      }

    catch(PDOException $e){
      echo "La conexión ha fallado: " . $e->getMessage();
    }
  }
}

?>
