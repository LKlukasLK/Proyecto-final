<?php
$res = mysqli_query($conexion, "SELECT * FROM usuarios");
?>
<div class="tabla-header">
    <h2><i class="fa-solid fa-users"></i> Gestión de Usuarios</h2>
</div>
<table class="tabla-admin">
    <thead>
        <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th></tr>
    </thead>
    <tbody>
        <?php while($f = mysqli_fetch_assoc($res)){ ?>
        <tr>
            <td><?php echo $f['id']; ?></td>
            <td><?php echo $f['nombre']; ?></td>
            <td><?php echo $f['email']; ?></td>
            <td><span class="badge <?php echo $f['rol']; ?>"><?php echo $f['rol']; ?></span></td>
            <td>
                <a href="#" class="btn-edit"><i class="fa-solid fa-user-gear"></i></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>