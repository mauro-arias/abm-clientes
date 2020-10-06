<?php

ini_set('display_errors','1');
ini_set('display_startup_errors','1');
ini_set('error_reporting', E_ALL); 


if(file_exists("clientes.json")){

    //Leer el archivo
    $contenido = file_get_contents("clientes.json");


    //Guardar en el array el json decodificado

    $aClientes = json_decode($contenido, true);

} else{
    $aClientes = array();
}

//Borrar
if (isset($_GET["id"]) && $_GET["id"] >= 0 && isset($_GET["do"]) && $_GET["do"] == "eliminar"){
    //Borrar imagen
    
    if (file_exists("archivos/" . $aClientes[$_GET["id"]]["imagen"])){
        unlink("archivos/". $aClientes[$_GET["id"]]["imagen"] );
    }
    
    unset($aClientes[$_GET["id"]]);
    file_put_contents("clientes.json", json_encode($aClientes));

    header("Location: index.php");
}

if($_POST){
    
    $nombre = $_POST["txtNombre"];
    $dni = $_POST["nbDNI"];
    $tel = $_POST["nbTel"];
    $correo = $_POST["txtCorreo"];
    $nombreImagen = "";


    if($_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
        $nombreAleatorio = date("Ymdhmsi");
        $archivo_temp = $_FILES["archivo"]["tmp_name"];
        $nombreArchivo = $_FILES["archivo"]["name"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreImagen = $nombreAleatorio . "." . $extension;
        move_uploaded_file($archivo_temp, "archivos/$nombreImagen");

    } 

    if(isset($nombreImagen) && $nombreImagen != "" && isset($_GET["id"]) && $aClientes[$_GET["id"]]["imagen"] != ""){
        unlink("archivos/" . $aClientes[$_GET['id']]['imagen']);
    }

    if($nombreImagen == ""){
        $nombreImagen = $aClientes[$_GET["id"]]["imagen"];
    }



    if (isset($_GET["id"]) && $_GET["id"] >= 0){
        //Actualizar
        $aClientes[$_GET["id"]] = array("nombre" => $nombre, "dni" => $dni, "tel" => $tel, "correo" => $correo, "imagen" => $nombreImagen);
    } else {
        //Nuevo
        $aClientes[] = array("nombre" => $nombre, "dni" => $dni, "tel" => $tel, "correo" => $correo, "imagen" => $nombreImagen);
    }

    
    file_put_contents("clientes.json", json_encode($aClientes));
}



?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Clientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.css">
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    
    <h1 class = "text-center">REGISTRO DE CLIENTES</h1>

    <div class="row">
    
        <div class="col-sm-6 col-12">
        
            <div class="container">
            
                <form action="" method = "POST" class = "form" enctype = "multipart/form-data">
                
                    <h5>DNI:</h5>
                    <input class = "form-control" type="number" name = "nbDNI" id = "nbDNI" required value = "<?php echo isset($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["dni"] : "" ?>">

                    <h5 class = "mt-2 ">Nombre:</h5>
                    <input class = "form-control" type="text" name = "txtNombre" id = "txtNombre" required value = "<?php echo isset($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["nombre"] : "" ?>">

                    <h5 class = "mt-2 ">Teléfono:</h5>
                    <input  class = "form-control"type="tel" name = "nbTel" id = "nbTel" required value = "<?php echo isset($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["tel"] : "" ?>">

                    <h5 class = "mt-2 ">Correo:</h5>
                    <input class = "form-control" type="email" name = "txtCorreo" id = "txtCorreo" required value = "<?php echo isset($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["correo"] : "" ?>">
                    
                    <h5 class = "mt-2">Archivo adjunto:</h5>
                    <input class = "d-block" type="file" name = "archivo" id = "archivo">

                    <button class = "mt-3 btn btn-info" type = "submit">Guardar<i class="fas fa-save ml-2"></i></i></button>
                
                </form>
            
            
            </div>

        </div>


        <div class="col-sm-6 col-12">
        
            
            <table class = "table border table-border table-hover">
            
                <tr>
                    <th>Imagen</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Editar</th>
                    <th>Eliminar</th>

                </tr>
                
                <tr>

                    <td> <img class = "img-thumbnail" src="img/logo.png" alt="logo"> </td>
                    <td>43607608</td>
                    <td>MAURO ARIAS CHARRAS</td>
                    <td>mauroacharras@hotmail.com</td>
                    <td><button class = "btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button></td>
                    <td><button class = "btn btn-sm btn-danger"><i class='fas fa-trash'></button></td>                

                </tr> 

                <?php if(empty($aClientes) == FALSE) { ?>

                        
                    

                    <?php foreach($aClientes as $key => $cliente) { ?>

                        <tr>
                            <td> <img class = "img-thumbnail" src="archivos/<?php echo $cliente["imagen"]; ?>" alt=""> </td>
                            <td> <?php echo $cliente["dni"]; ?> </td>
                            <td> <?php echo strtoupper($cliente["nombre"]); ?> </td>
                            <td> <?php echo $cliente["correo"]; ?> </td>
                            <td> <a href = "index.php?id=<?php echo $key ?>" class = "btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a> </td>
                            <td> <a href = "index.php?id=<?php echo $key ?>&do=eliminar" class = "btn btn-sm btn-danger"><i class='fas fa-trash'></a></td>
                        </tr>

                    <?php } ?>    
                <?php } ?>   
            
            </table>
            
            <div class="btn-container align-items-right">
            
                <a href="index.php" class ="btn btn-success rounded-circle"><i class="fas fa-plus"></i></a>
            
            </div>

                    
        
        </div>

        
        
        
    
    </div>



</body>
</html>