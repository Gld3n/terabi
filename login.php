<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$BaseDeDatos = "galaxy_travel";

$enlace = mysqli_connect($servidor, $usuario, $clave, $BaseDeDatos);
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
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
      width: 320px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      border: 1px solid #0f4c81;
      transition: box-shadow 0.3s ease;
    }

    form:hover {
      box-shadow: 0 0 30px 8px #3ea0ff;
    }

    input[type="text"],
    input[type="password"] {
      background: transparent;
      border: 2px solid #0f4c81;
      border-radius: 6px;
      padding: 12px 15px;
      color: #c1ffea;
      font-size: 16px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input[type="text"]::placeholder,
    input[type="password"]::placeholder {
      color: #7aaedb;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #3ea0ff;
      box-shadow: 0 0 8px #3ea0ff;
      background: rgba(15, 76, 129, 0.1);
    }

    input[type="submit"],
    button.button {
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
    button.button:hover {
      background: #3ea0ff;
      box-shadow: 0 0 12px #3ea0ff;
    }

    button.button {
      margin-top: 10px;
    }

    input[type="submit"]:active,
    button.button:active {
      transform: scale(0.98);
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
    <input type="submit" name="login" value="Iniciar Sesión">
    <button type="button" class="button" onclick="window.location.href='registro.php'">Registrarse</button>
</form>

<?php

if (isset($_POST['login'])) {
    $usuario_input = mysqli_real_escape_string($enlace, $_POST['usuario']);
    $contrasena_input = $_POST['contrasena'];

    $consulta = "SELECT * FROM clientes WHERE usuario='$usuario_input'";
    $resultado = mysqli_query($enlace, $consulta);

    if (mysqli_num_rows($resultado) == 1) {
        $cliente = mysqli_fetch_assoc($resultado);

        if (password_verify($contrasena_input, $cliente['contrasena'])) {
            $_SESSION['usuario'] = $cliente['usuario'];
            $_SESSION['nombre'] = $cliente['nombre'];
            $_SESSION['id'] = $cliente['id'];

            mysqli_close($enlace);
            echo "<script>window.location.href = 'chatbot.php';</script>";
        } else {
            echo "<script>alert('Contraseña incorrecta');</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado');</script>";
    }
}
?>

</body>
</html>
