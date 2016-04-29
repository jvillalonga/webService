<div class="section">
  <h2>WSRequest</h2>
  <table id="taula">
    <thead>
      <tr>
        <th>TrId</th>
        <th>Tipo</th>
        <th>txId</th>
        <th>statusCode</th>
        <!-- <th>statMessage</th> -->
        <!-- <th>token</th> -->
        <th>msisdn</th>
        <!-- <th>shortcode</th> -->
        <!-- <th>amount</th> -->
        <!-- <th>text</th> -->
        <th>Fecha</th>
        <!-- <th>Opciones</th> -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($regRequest as $reg_item): ?>
        <tr>
          <td><?php echo $reg_item['transaction']; ?></td>
          <td><?php echo $reg_item['tipo']; ?></td>
          <td><?php echo $reg_item['txId']; ?></td>
          <td><?php echo $reg_item['statusCode']; ?></td>
          <!-- <td> <?php echo $reg_item['statusMessage']; ?></td> -->
          <!-- <td><?php echo substr($reg_item['token'],0,15) . ' ...'; ?></td> -->
          <td><?php echo $reg_item['msisdn']; ?></td>
          <!-- <td><?php echo $reg_item['shortcode']; ?></td> -->
          <!-- <td><?php echo $reg_item['amount']; ?> $</td> -->
          <!-- <td><?php echo $reg_item['text']; ?></td> -->
          <td><?php echo $reg_item['Fecha']; ?></td>
          <!-- <td class="tdOptions">

          </td> -->
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
    <form action="getToken" method="post">
      <input type="submit" name="submit" value="get Token"/>
    </form>
</div>
