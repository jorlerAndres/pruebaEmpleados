<?php
include '../Modelo/empleadoModel.php';

class empleadoControlador{

 public $modelo;


 function __construct(){

    $this->modelo=new EmpleadoModel();

 }
 public function getEmpleados(){
        
    $data=isset($_GET['id']) ? $this->getEmpleadosById($_GET['id']) :  $this->getEmpleadosAll();
   
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
            <td><i class='bi bi-pencil-square editar' data-id={$res -> id} ></td>
            <td><i class='bi bi-trash3 borrar' data-id={$res -> id}></i></td>
        </tr>";

    }
    return $tabla;

  }

  public function getEmpleadosById($id){
    
    $data= $this->modelo->getEmpleadosById($id);

    if (is_object($data[0])) {

        $data = get_object_vars($data[0]);

        $roles=explode(',',$data['rol']);
        $data['roles']= $roles;
       
        return json_encode($data);
    }
  }

  public function deleteEmpleado($id){
    
    $data= $this->modelo->deleteEmpleado($id);
    echo $data;
    
  }

  public function setEmpleado($params){

     $data=$params['id']=='no' ? $this->modelo->insertEmpleado($params) : $this->modelo->updateEmpleado($params);
    echo $data;  
    
  }

}

?>