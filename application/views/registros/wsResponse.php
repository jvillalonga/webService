<div class="section">
  <h2>WSResponse</h2>
  <table id="taula2">
    <thead>
      <tr>
        <th>txId</th>
        <th>token</th>
        <th>statusCode</th>
        <th>statusMessage</th>
        <th>Fecha</th>
        <!-- <th>Opciones</th> -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($regResponse as $reg_item): ?>
        <tr>
          <td><?php echo $reg_item['txId']; ?></td>
          <td><?php echo $reg_item['token']; ?></td>
          <td><?php echo $reg_item['statusCode']; ?></td>
          <td> <?php echo $reg_item['statusMessage']; ?> $</td>
          <td><?php echo $reg_item['fecha']; ?></td>
          <!-- <td class="tdOptions">

          </td> -->
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
