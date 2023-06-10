<?php
include '../Modelo/empleadoModel.php';

class empleadoControlador{

 public $modelo;


 function __construct(){

    $this->modelo=new EmpleadoModel();

 }
 public function getEmpleados(){
        
 
  
    $data=isset($_GET['email']) ? $this->getEmpleadosByEmail($_GET['email']) :  $this->getEmpleadosAll();
   
    echo $data;
 }

  public function getEmpleadosAll(){
    
    $data= $this->modelo->getEmpleadosAll();

    $tabla= "";
  
    foreach($data as $res) {

      
        $tabla.=  "<tr>
            <td>{$res -> nombre}</td>
            <td>{$res -> email}</td>
            <td>{$res -> sexo}</td>
            <td>{$res -> area}</td>
            <td>{$res -> boletin}</td>
            <td>{$res -> rol}</td>
            <td><i class='bi bi-pencil-square editar' data-email={$res -> email} ></td>
            <td><i class='bi bi-trash3 borrar' data-email={$res -> email}></i></td>
        </tr>";

    }

    return $tabla;

  }

  public function getEmpleadosByEmail($email){
    
    $data= $this->modelo->getEmpleadosByEmail($email);

    

    if (is_object($data[0])) {

        $data = get_object_vars($data[0]);

        $roles=explode(',',$data['rol']);
        $data['roles']= $roles;
       
        return json_encode($data);
    }
   
  }

  public function deleteEmpleado($email){
   
   $data= $this->modelo->deleteEmpleado($email);
    echo $data;
    
   
  }

  public function setEmpleado($params){
    
  
     $data=$_POST['correo']=='si' ? $this->modelo->insertEmpleado($params) :  $this->modelo->updateEmpleado($params);
    echo $data; 
     
    
   }

  
}

?>