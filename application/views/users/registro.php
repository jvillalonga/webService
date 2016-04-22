<div>
  <h2>Nuevo usuario</h2>
  <div id="form">
    <div class="red">
      <?php echo validation_errors(); ?>
    </div>
    <?php echo form_open('users/registrar'); ?>

    <input type="input" name="user" placeholder="User"/><br />
    <input type="password" name="pass" placeholder="Password"/><br />
    <input type="input" name="telefono" placeholder="Teléfono"/><br />
    <input type="input" name="fondos" placeholder="Fondos"/><br />

    <input type="submit" name="submit" value="Registrar" />

  </form>
  </div>
</div>
