<?php 
/**
 * Draw the Login Form
 */
function drawLogin(){ ?>
  <section id="login">
    <h2>Sign In</h2>
    <span>
      Don't have an account yet? <a href="register.php">Register</a> today!
    </span>
    <form action="../actions/action_login.php" method="post">
      <label>Username
        <input type="text" placeholder="Username" name="username">
      </label>
      <label>Password
        <input type="password" placeholder="Password" name="password">
      </label>
      <input type="submit" value="Login">
    </form>

    <!-- Imprimir mensagens de erro do login -->
    <?php  drawMessages(); ?> 
      
  </section>
<?php }  ?>

<?php 
/**
 * Draw the register Form
 */
function drawRegister(){ ?>
  <section id="register">
      <h2>Register</h2>
      <form action="../actions/action_register.php" method="post">
          <label for="username">Username
          </label>
          <input type="text" id="username" name="username" placeholder="Username" required>
          <span class="hint">Only lowercase, numbers and underscore, at least 5 characters</span>

          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" required>
          <span class="hint">One uppercase, one lowercase, one symbol, 1 number, at least 8 characters</span>

          <label for="password_repeat">Repeat Password</label>
          <input type="password" id="password_repeat" name="password_repeat" placeholder="Repeat Password" required>
          <span class="hint">Must match password</span>

          <input type="submit" value="Register">
          
      </form>

      <!-- Imprimir mensagens de erro do login -->
      <?php  drawMessages(); ?> 
    
  </section>
<?php } ?>

<?php 
/**
 * Draw Edit Profile Form
 */
function drawEditProfile(){ ?>
  <section id="editProfile">
      <h2>Edit Profile</h2>
      <form action="../actions/action_edit_profile.php" method="post">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
        <label for="oldPassword">Enter Password - Required</label>
        <input type="password" id="oldPassword" name="oldPassword" placeholder="Password" required>
        <span class="hint">Incorrect Password</span>

        <label for="editUsername">Edit Username</label>
        <input type="text" id="editUsername" name="editUsername" placeholder= <?=$_SESSION['username']?>>
        <span class="hint">Only lowercase, numbers and underscore, at least 5 characters</span>

        <label for="editPassword">Edit Password</label>
        <input type="password" id="editPassword" name="editPassword" placeholder="Enter New Password">
        <span class="hint">One uppercase, one lowercase, one symbol, 1 number, at least 8 characters</span>

        <input type="password" id="edit_password_repeat" name="edit_password_repeat" placeholder="Repeat New Password">
        <span class="hint">Must match password</span>

        <input type="submit" name= "submit_btn" value="Submit">
        <input type="submit" name= "deleteprofile_btn" value="delete profile" formaction="../actions/action_delete_profile.php">
      </form>
    
      <!-- Imprimir mensagens de erro do login -->
      <?php  drawMessages();?>
    
  </section>
<?php } ?>

