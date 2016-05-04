<div class="section">
  <h2><?php echo $title; ?></h2>
  <table id="taula">
    <thead>
      <tr>
        <th>Id</th>
        <th>User</th>
        <th>Teléfono</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        <!-- <th>Opciones</th> -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($regs as $reg_item): ?>
        <tr>
          <td><?php echo $reg_item['id']; ?></td>
          <td><?php echo $reg_item['user']; ?></td>
          <td><?php echo $reg_item['telefono']; ?></td>
          <td> <?php echo $reg_item['cantidad']; ?> $</td>
          <td><?php echo $reg_item['fecha']; ?></td>
          <!-- <td class="tdOptions">

          </td> -->
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
