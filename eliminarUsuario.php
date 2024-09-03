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

    $id_usuario = $_POST['id_usuario'];

    $sql_select = "SELECT nombre, apellido, email, contrasena FROM usuarios WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_usuario);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows > 0) {
        $resultado = $result->fetch_assoc();

        $sql_delete = "DELETE FROM usuarios WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_usuario);
        $stmt_delete->execute();

        if ($stmt_delete->affected_rows > 0) {
            echo "<p>El usuario con ID $id_usuario ha sido eliminado exitosamente.</p>";
        } else {
            echo "<p>No se pudo eliminar el usuario.</p>";
        }

        $stmt_delete->close();
    } else {
        echo "<p>No se encontró ningún usuario con el ID especificado.</p>";
    }

    $stmt_select->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
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
        input[type="text"] {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #c82333;
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
    <h1>Eliminar Usuario por ID</h1>
    <form action="eliminar_usuario.php" method="post">
        <input type="text" name="id_usuario" placeholder="Ingrese el ID del usuario" required>
        <input type="submit" value="Eliminar Usuario">
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
