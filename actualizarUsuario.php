<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registros";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    $sql = "UPDATE usuarios SET nombre=?, apellido=?, email=?, contrasena=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $contrasena, $id_usuario);

    if ($stmt->execute()) {
        echo "<p>El usuario con ID $id_usuario ha sido actualizado exitosamente.</p>";
        
        $sql_select = "SELECT nombre, apellido, email, contrasena FROM usuarios WHERE id = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $id_usuario);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($result->num_rows > 0) {
            $resultado = $result->fetch_assoc();
        }

        $stmt_select->close();
    } else {
        echo "<p>No se pudo actualizar el usuario.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Actualizar Usuario por ID</h1>
    <form action="actualizar_usuario.php" method="post">
        <input type="text" name="id_usuario" placeholder="Ingrese el ID del usuario" required>
        <input type="text" name="nombre" placeholder="Ingrese el nuevo nombre" required>
        <input type="text" name="apellido" placeholder="Ingrese el nuevo apellido" required>
        <input type="email" name="email" placeholder="Ingrese el nuevo email" required>
        <input type="password" name="contrasena" placeholder="Ingrese la nueva contraseña" required>
        <input type="submit" value="Actualizar Usuario">
    </form>

    <?php if(isset($resultado)): ?>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Contraseña</th>
            </tr>
            <tr>
                <td><?= $resultado['nombre']; ?></td>
                <td><?= $resultado['apellido']; ?></td>
                <td><?= $resultado['email']; ?></td>
                <td><?= $resultado['contrasena']; ?></td>
            </tr>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
