<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat con Astroneer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at center, #0b0f33, #000);
            color: #f1f1f1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        h1 {
            color: #00bfff;
            margin-bottom: 20px;
            text-shadow: 1px 1px 4px #000;
        }

        #chatlog {
            width: 100%;
            max-width: 600px;
            height: 360px;
            overflow-y: auto;
            border: 1px solid #2c2c2c;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 15px;
            box-shadow: 0 0 12px #00000055;
        }

        #chatlog div {
            margin-bottom: 12px;
        }

        input[type="text"] {
            width: calc(100% - 110px);
            max-width: 480px;
            padding: 10px;
            border: none;
            border-radius: 6px;
            margin-top: 15px;
            background-color: #1c1c1c;
            color: #fff;
        }

        button {
            padding: 10px 20px;
            margin-top: 15px;
            margin-left: 10px;
            border: none;
            border-radius: 6px;
            background-color: #00bfff;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        button:hover {
            background-color: #009acd;
        }
    </style>
</head>
<body>
    <h1>Chat con Astroneer</h1>

    <div id="chatlog"></div>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 600px;">
        <input type="text" id="userInput" placeholder="Escribe tu mensaje...">
        <button onclick="sendMessage()">Enviar</button>
    </div>

    <script src="scripts/script_chat.js"></script>
</body>
</html>
