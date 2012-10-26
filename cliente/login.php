<?php
session_start();
require_once '../config.php';
?>
<html>
<head>
</head>
<?php
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoUsuarios.class.php';
$idusuario = $_POST['user'];
$pass = $_POST['pass'];
$cat_usuarios = new CatalogoUsuarios();

$usuario = @$cat_usuarios->ObtenerUsuario($idusuario);

if (!empty($usuario->Usuario_)) :
	if (($usuario->Pass_ == $pass) && ($usuario->Usuario_ = $usuario)):		   
		   $_SESSION['usuario'] = 'admin';                    
		 	print 'Bienvenido(a) si no puede entrar <a href="../admin/adminpersonas.php">click aqui</a>';		   
                         
	endif;
		
else:

		    print 'Uno de los datos no es correcto.'; 
    
endif;
?>

</body>



