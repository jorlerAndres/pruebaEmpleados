<?php
include '../Controlador/empleadoControlador.php';
header('Content-Type: application / x-www-form-urlencoded');
$empleado=new empleadoControlador();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
       
        $empleado->setEmpleado($_POST);
        break;
    case 'GET':
        
        $empleado->getEmpleados($_GET);
       
        break;
    case 'DELETE':
    
        $param = file_get_contents("php://input"); 

        $empleado->deleteEmpleado($param);
      
        break;
    case 'PUT':

        $params = file_get_contents("php://input"); 
        $params=json_decode($params);
         var_dump($params);
        //$empleado->deleteEmpleado($param);
        break;

    default:
        # code...
        break;
}

?>
