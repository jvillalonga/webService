<div class="section">
  <h2><?php echo $title; ?></h2>
  <table id="taula">
    <thead>
      <tr>
        <th>Id</th>
        <th>User</th>
        <th>Operación</th>
        <th>Fecha</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($regs as $reg_item): ?>
        <tr>
          <td><?php echo $reg_item['id']; ?></td>
          <td><?php echo $reg_item['user']; ?></td>
          <td> <?php echo $reg_item['tipo']; ?></td>
          <td><?php echo $reg_item['fecha']; ?></td>
          <td class="tdOptions">
            <!-- <form action="alta" method="post">
              <input type="hidden" name="user" value="<?php echo $reg_item['user']; ?>"/>
              <input type = "submit" name = "submit" value = "Dar alta" <?php if ($reg_item['estado'] == 'Alta'){ echo "disabled"; }; ?>/>
            </form>
            <form action="baja" method="post">
              <input type="hidden" name="user" value="<?php echo $reg_item['user']; ?>"/>
              <input type = "submit" name = "submit" value = "Dar baja" <?php if ($reg_item['estado'] == 'Baja'){ echo "disabled"; }; ?>/>
            </form>
            <form action="cobrar" method="post">
              <input type="hidden" name="user" value="<?php echo $reg_item['user']; ?>"/>
              <input type = "submit" name = "submit" value = "Cobrar" <?php if ($user_item['estado'] == 'Baja'){ echo "disabled"; }; ?>/>
            </form>-->
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
