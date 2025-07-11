<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$BaseDeDatos = "galaxy_travel";

$enlace = mysqli_connect($servidor, $usuario, $clave, $BaseDeDatos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
    <style>
    @import url("https://fonts.googleapis.com/css?family=Fira+Mono:400");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: radial-gradient(circle, #131111 0%, black 17%, black 27%);
      height: 100vh;
      font-family: "Fira Mono", monospace;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #c1ffea;
      overflow: hidden;
    }

    form {
      background: rgba(10, 10, 10, 0.85);
      padding: 40px 50px;
      border-radius: 12px;
      box-shadow: 0 0 20px 5px #0f4c81;
      width: 360px;
      display: flex;
      flex-direction: column;
      gap: 18px;
      border: 1px solid #0f4c81;
      transition: box-shadow 0.3s ease;
    }

    form:hover {
      box-shadow: 0 0 30px 8px #3ea0ff;
    }

    input[type="text"],
    input[type="password"],
    input[type="number"] {
      background: transparent;
      border: 2px solid #0f4c81;
      border-radius: 6px;
      padding: 12px 15px;
      color: #c1ffea;
      font-size: 16px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input[type="text"]::placeholder,
    input[type="password"]::placeholder,
    input[type="number"]::placeholder {
      color: #7aaedb;
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="number"]:focus {
      outline: none;
      border-color: #3ea0ff;
      box-shadow: 0 0 8px #3ea0ff;
      background: rgba(15, 76, 129, 0.1);
    }

    input[type="submit"],
    input[type="reset"],
    button.button2 {
      background: #0f4c81;
      border: none;
      border-radius: 6px;
      padding: 12px 20px;
      color: #c1ffea;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease, box-shadow 0.3s ease;
      user-select: none;
    }

    input[type="submit"]:hover,
    input[type="reset"]:hover,
    button.button2:hover {
      background: #3ea0ff;
      box-shadow: 0 0 12px #3ea0ff;
    }

    input[type="submit"]:active,
    input[type="reset"]:active,
    button.button2:active {
      transform: scale(0.98);
    }

    button.button2 {
      margin-top: 10px;
      border-radius: 6px;
    }

    @media (max-width: 400px) {
      form {
        width: 90%;
        padding: 30px 20px;
      }
    }
    </style>
</head>
<body>

<form action="#" method="post">
    <input type="text" name="usuario" placeholder="Nombre de usuario" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="number" name="edad" placeholder="Edad" required>
    <input type="text" name="cedula" placeholder="Cédula de identidad" required>
    <input type="submit" name="registro" value="Registrar">
    <input type="reset" value="Limpiar">
    <button type="button" class="button2" onclick="window.location.href='index.php'">Volver al inicio de sesión</button>
</form>

<?php
if (isset($_POST['registro'])) {
    $usuarioInput = mysqli_real_escape_string($enlace, $_POST['usuario']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $nombre = mysqli_real_escape_string($enlace, $_POST['nombre']);
    $edad = (int)$_POST['edad'];
    $cedula = mysqli_real_escape_string($enlace, $_POST['cedula']);
    $fechaRegistro = date("Y-m-d");

    // Verificar si el usuario o la cédula ya existen
    $verificar = "SELECT * FROM clientes WHERE usuario = '$usuarioInput' OR cedula_identidad = '$cedula'";
    $resultado = mysqli_query($enlace, $verificar);

    if (mysqli_num_rows($resultado) > 0) {
        echo "<script>alert('El nombre de usuario o la cédula ya están registrados');</script>";
    } else {
        $insertar = "INSERT INTO clientes (usuario, contrasena, nombre, edad, cedula_identidad, fecha_registro)
                     VALUES ('$usuarioInput', '$contrasena', '$nombre', $edad, '$cedula', '$fechaRegistro')";
        if (mysqli_query($enlace, $insertar)) {
            echo "<script>alert('Registro exitoso'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error al registrar');</script>";
        }
    }
}
?>

</body>
</html>
