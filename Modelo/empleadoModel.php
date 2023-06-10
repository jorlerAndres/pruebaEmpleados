<?php
include 'baseModel.php';

/**
 *En esta clase estan todas los llamados a base de datos requeridos para la aplicacion
 */
class EmpleadoModel extends baseModel

{   
   

    public function getEmpleadosAll(){

        $sql= " SELECT emp.nombre,email,a.nombre AS area,
                CASE WHEN sexo='M' THEN 'Masculino' ELSE 'Femenino' END AS sexo,
                CASE WHEN boletin=1 THEN 'Si' ELSE 'No' END AS boletin, 
                GROUP_CONCAT( rol.nombre) as rol
                FROM empleado emp 
                JOIN areas a         on a.id=emp.area_id
                JOIN empleado_rol er on er.empleado_id=emp.id
                JOIN roles rol       on rol.id=er.rol_id  
                GROUP BY emp.nombre";

        $query = $this->bd -> prepare($sql); 
        $query -> execute(); 
        $result = $query -> fetchAll(PDO::FETCH_OBJ); 

       
        return $result;
    }

    public function getEmpleadosByEmail($email){

        try {
          

            $sql= " SELECT emp.nombre,email,emp.area_id AS area_id,
                    sexo,boletin,descripcion,
                    GROUP_CONCAT( rol.nombre) as rol
                    FROM empleado emp 
                    JOIN areas a         on a.id=emp.area_id
                    JOIN empleado_rol er on er.empleado_id=emp.id
                    JOIN roles rol       on rol.id=er.rol_id  
                    WHERE email like :email
                    GROUP BY emp.nombre";

            $query = $this->bd -> prepare($sql); 
            $query->bindParam(':email',$email,PDO::PARAM_STR);
            $query -> execute(); 
            $result = $query -> fetchAll(PDO::FETCH_OBJ); 
            return $result;
          
        } catch (\Throwable $th) {
            return false;
        }
       
        
    }

    public function insertEmpleado($params){

         $boletin=isset($params['boletin'])? 1 : 0;

         $id=$this->lastId() +1 ;

         try {
            $sql= "INSERT INTO empleado(id,nombre,email,sexo,area_id,boletin,descripcion) VALUES(:id,:nombre,:email,:sexo,:area,:boletin,:descripcion)";

            $query = $this->bd -> prepare($sql); 
            $query->bindParam(':id',$id,PDO::PARAM_INT);
            $query->bindParam(':nombre',$params['nombre'],PDO::PARAM_STR);
            $query->bindParam(':email',$params['email'],PDO::PARAM_STR);
            $query->bindParam(':sexo',$params['sexo'],PDO::PARAM_STR);
            $query->bindParam(':area',$params['area'],PDO::PARAM_INT);
            $query->bindParam(':boletin',$boletin,PDO::PARAM_INT);
            $query->bindParam(':descripcion',$params['descripcion'],PDO::PARAM_STR);
            $query -> execute(); 
            $lastInsertId = $this->bd->lastInsertId();
            $this->insertRol($params, $lastInsertId);
          
            return true;

        } catch (\Throwable $th) {
            return $th;
        } 
    }

    public function updateEmpleado($params){

        $boletin=isset($params['boletin'])? 1 : 0;
        try {
            $sql= "UPDATE empleado set nombre=:nombre, email=:email, sexo=:sexo,area_id=:area,boletin=:boletin, descripcion=:descripcion WHERE email=:correo";

            $query = $this->bd -> prepare($sql); 
            $query->bindParam(':nombre',$params['nombre'],PDO::PARAM_STR);
            $query->bindParam(':email',$params['email'],PDO::PARAM_STR);
            $query->bindParam(':sexo',$params['sexo'],PDO::PARAM_STR);
            $query->bindParam(':area',$params['area'],PDO::PARAM_INT);
            $query->bindParam(':boletin',$boletin,PDO::PARAM_INT);
            $query->bindParam(':descripcion',$params['descripcion'],PDO::PARAM_STR);
            $query->bindParam(':correo',$params['correo'],PDO::PARAM_STR);
            $query -> execute(); 
            $result = $query -> fetchAll(PDO::FETCH_OBJ); 

            return true;

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function deleteEmpleado($email){

        try {
           
             $sql= "DELETE FROM empleado WHERE email = :email";
            
            $query = $this->bd -> prepare($sql); 
            $query->bindParam(':email',$email,PDO::PARAM_STR);
            $query -> execute(); 

            return true;
            

        } catch (\Throwable $th) {

            return false;
        }     
        
    }

    public function lastId(){

        try {
           
            $stmt = $this->bd->prepare("SELECT MAX(id) AS id FROM empleado");
            $stmt -> execute();
            $invNum = $stmt -> fetch(PDO::FETCH_ASSOC);
            $max_id = $invNum['id'];
            return  $max_id;

        } catch (\Throwable $th) {

            return false;
        }     
        
    }

    public function insertRol($params,$id){

        $array=array();

        $array['desarrollador']= $params['Desarrollador']??null;
        $array['analista']= $params['Analista']?? null;
        $array['Diseñador']= $params['Diseñador']?? null;

        foreach ($array as $key => $value) {
            
            if(!is_null($value)){
                $sql= "INSERT INTO empleado_rol(empleado_id,rol_id) VALUES(:empleado_id,:rol)";

                $query = $this->bd -> prepare($sql); 
                $query->bindParam(':empleado_id',$id,PDO::PARAM_INT);
                $query->bindParam(':rol',$value,PDO::PARAM_STR);
               
                $query -> execute(); 
    
            }
        }

    }
 }


?>