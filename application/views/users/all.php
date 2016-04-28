
<div class="section">
  <h2><?php echo $title; ?></h2>
  <table id="taula">
    <thead>
      <tr>
        <th>User</th>
        <th>Teléfono</th>
        <th>Estado</th>
        <th>Último cobro</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user_item): ?>
        <tr>
          <td><?php echo $user_item['user']; ?></td>
          <td><?php echo $user_item['telefono']; ?></td>
          <td><?php echo $user_item['estado']; ?></td>
          <td><?php echo $user_item['ultimoCobro']; ?></td>
          <td class="tdOptions">
            <form action="alta" method="post">
              <input type="hidden" name="user" value="<?php echo $user_item['user']; ?>"/>
              <input type="hidden" name="tel" value="<?php echo $user_item['telefono']; ?>"/>
              <input type="hidden" name="cantidad" value="5"/>
              <input type = "submit" name = "submit" value = "Dar alta (5$)" <?php if ($user_item['estado'] == 'Alta'){ echo "disabled"; }; ?>/>
            </form>
            <form action="baja" method="post">
              <input type="hidden" name="user" value="<?php echo $user_item['user']; ?>"/>
              <input type="hidden" name="tel" value="<?php echo $user_item['telefono']; ?>"/>
              <input type = "submit" name = "submit" value = "Dar baja" <?php if ($user_item['estado'] == 'Baja'){ echo "disabled"; }; ?>/>
            </form>
            <form action="getToken" method="post">
              <input type="hidden" name="user" value="<?php echo $user_item['user']; ?>"/>
              <input type="hidden" name="tel" value="<?php echo $user_item['telefono']; ?>"/>
              <input type="number" step="0.01" min="0" name="cantidad" value="5"
                <?php if ($user_item['estado'] == 'Baja'){ echo "disabled"; }; ?>
              />
              <input type = "submit" name = "submit" value = "Cobrar"
              <?php if ($user_item['estado'] == 'Baja'){ echo "disabled"; }; ?>/>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <form action="cobrarSuscritos" method="post">
    <input type="number" step="0.01" min="0" name="cantidad" value="0.50"/>
    <input type = "submit" name = "submit" value = "Cobrar suscritos"/>
  </form>
</div>
