<?php
    include("conexion.php"); /*Conectamos la base de datos*/

    /*La variable $_SERVER  es un variable de entorno superglobal que contiene toda la informaciòn sobre los encabezados, rutas y ubicacioens de los scripts.
    $_SERVER['REQUEST_METHOD'], almacena el metodo HTTP con el cual se hizo al solicitud actual, los mas comunes son POST y GET
    $_POST es una variable super global, la cual especifica que almacena los datos enviados a travès del formulario utilizando el método POST.*/

    if($_SERVER['REQUEST_METHOD']==='POST') {
        $username_registro=$_POST['username_registro']; 
        $email_registro=$_POST['email_registro'];
        $pass_registro=$_POST['pass_registro'];
        $confirm_pass=$_POST['confirm_pass'];

        // verificar que las contraseñas coincidan 

        if($pass_registro===$confirm_pass) {
            $password=password_hash($pass_registro, PASSWORD_BCRYPT); //esta función permite encriptar el password.

            /*prueba imprimir los datos antes de insertarlos a la BD
             echo "username: $username_registro <br>";
             echo "Email:  $email_registro <br>";
             echo "contraseña: $password <br>";*/

             $chequear_User=$conn->prepare("SELECT * FROM registro WHERE username_registro=?");
             $chequear_User->bind_param("s",$username_registro); //vincular el parametro $username_registro
             $chequear_User->execute(); //Ejecuta la consulta
             $resultado= $chequear_User->get_result(); //obtiene el resultado de la consulta
             
             if($resultado->num_rows>0) {
                //Si el nombre de usuario ya existe

                echo '<script>alert("El nombre de usuario ya existe. Por favor elige otro.");</script>';
                echo '<script>window.location.href="form_registrarse.php";</script>';

             }//cierra el if cuando existe el nombre de usuario
             else {
                   //Si no existe el usuario, inserta a la base de datos
                   
                   $insertar_bd=$conn->prepare("INSERT INTO registro ( username_registro, email_registro, pass_registro) VALUES (?,?,?)");

                   if( $insertar_bd->execute([$username_registro, $email_registro, $password])) {
                        header("location:form_login.php"); //redirecciona a la página del logeo
                   }//Cierra el if donde permite insertar datos a la BD
                   else {
                    echo "Error ".$insertar_bd->error;
                   }
             }//Cierra el else en caso de que no existe el nombre de usuario en la bd
        } /*Cierra el if de comparaciòn de las contraseñas*/
        else {
            echo '<script>alert("Las contraseñas no coinciden")</script>';
            echo '<script>window.location.href="form_registrarse.php"</script>';
        }
    
    } /*Cierra el if principal de la comparaciòn del mètodo POST*/
   
?>