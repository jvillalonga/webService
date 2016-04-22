<?php $nimFondos=5;?>

<div>
  <h2><?php echo $title; ?></h2>
  <table id="taula">
    <thead>
      <tr>
        <th>User</th>
        <th>Teléfono</th>
        <th>Fondos</th>
        <th>Estado</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user_item): ?>
        <tr>
          <td><?php echo $user_item['user']; ?></td>
          <td><?php echo $user_item['telefono']; ?></td>
          <td
          <?php if ($user_item['fondos'] < $nimFondos) { echo "class='red'";};?>
          > <?php echo $user_item['fondos']; ?> $</td>
          <td><?php if ($user_item['alta'] == 1){ echo "Alta"; } else { echo "Baja";}; ?></td>
          <td class="tdOptions">
            <form action="alta" method="post">
              <input type="hidden" name="user" value="<?php echo $user_item['user']; ?>"/>
              <input type = "submit" name = "submit" value = "Dar alta" <?php if ($user_item['alta'] == 1 || $user_item['fondos'] < $nimFondos ){ echo "disabled"; }; ?>/>
            </form>
            <form action="baja" method="post">
              <input type="hidden" name="user" value="<?php echo $user_item['user']; ?>"/>
              <input type = "submit" name = "submit" value = "Dar baja" <?php if ($user_item['alta'] == 0){ echo "disabled"; }; ?>/>
            </form>
            <form action="cobrar" method="post">
              <input type="hidden" name="user" value="<?php echo $user_item['user']; ?>"/>
              <input type = "submit" name = "submit" value = "Cobrar" <?php if ($user_item['alta'] == 0){ echo "disabled"; }; ?>/>
            </form>
            <?php if ($user_item['fondos'] < $nimFondos){ echo "<p class='red'>Fondos insuficientes</p>"; }; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
