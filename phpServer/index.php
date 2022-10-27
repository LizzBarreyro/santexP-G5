<?php

include 'conexion.php';


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$json = file_get_contents('php://input'); // RECIBE EL JSON DE ANGULAR

$params = json_decode($json); // DECODIFICA EL JSON Y LO GUARADA EN LA VARIABLE
 


$pdo = new Conexion();

if ($_SERVER['REQUEST_METHOD']=='GET') {

    if (isset($_GET['id'])) {
        $sql = $pdo->prepare("SELECT * FROM usuarios WHERE id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql-> execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        
        header("HTTP/1.1 200 OK");
        
        echo json_encode($sql-> fetchAll() );
        exit;
    }else{
	

$sql = $pdo->prepare("SELECT * FROM usuarios");
$sql-> execute();
$sql->setFetchMode(PDO::FETCH_ASSOC);

header("HTTP/1.1 200 OK");

echo json_encode($sql-> fetchAll() );
exit;

    }

}

//Insertar registro
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sql = "INSERT INTO usuarios (nombre_usuario,email,nombre,apellido,contrasena,rol_usuario) VALUES(:nombre_usuario, :email, :nombre, :apellido, :contrasena, :rol_usuario)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre_usuario', $params->nombre_usuario);
    $stmt->bindValue(':e-mail', $params->email);
    $stmt->bindValue(':nombre', $params->nombre);
    $stmt->bindValue(':apellido', $params->apellido);
    $stmt->bindValue(':contrasena', $params->contrasena);
    $stmt->bindValue(':rol_usuario', $params->rol_usuario);
    $stmt->execute();
    $idPost = $pdo->lastInsertId(); 
    if($idPost)
    {
        header("HTTP/1.1 200 Ok");
        echo json_encode('El registro se agrego correctamente');
        exit;
    }
}

//Actualizar registro
if($_SERVER['REQUEST_METHOD'] == 'PUT')
{		
    $sql = "UPDATE usuarios SET nombre_usuario=:nombre_usuario, email=:email, nombre=:nombre, apellio=:apellido, contrasena=:contrasena, rol_usuario=:rol_usuario WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre_usuario', $params->nombre_usuario);
    $stmt->bindValue(':email', $params->email);
    $stmt->bindValue(':nombre', $params->nombre);
    $stmt->bindValue(':apellido', $params->apellido);
    $stmt->bindValue(':contrasena', $params->contrasena);
    $stmt->bindValue(':rol_usuario', $params->rol_usuario);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    echo json_encode('El registro se actualizo correctamente');

    exit;
}
//Eliminar registro
if($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    $sql = "DELETE FROM usuarios WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    echo json_encode('El registro se elimino correctamente');
    exit;
}

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");

?>