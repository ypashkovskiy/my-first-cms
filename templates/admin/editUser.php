  <?php include "templates/include/headerUsers.php" ?>
  <?php include "templates/admin/include/headerUsers.php" ?>
  
  
  <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formUser']?>" method="post">
            <input type="hidden" name="userId" value="<?php echo $results['users']->userId ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
   
    <?php } ?>

            <ul>

              <li>
                <label for="Username">User Name</label>
                <input type="text" name="Username" id="Username" placeholder="Name of the user" required autofocus maxlength="255" value="<?php echo ( $results['users']->userName)?>" />
              </li>

              <li>
                <label for="Password">Password</label>
                <input type="text" name="Password" id="Password" placeholder="Enter your password" required maxlength="60" value="<?php echo ( $results['users']->password)?>" />
              </li>

                  

              <li>
                 <label for="Active">Active</label> 
                 <input type="hidden" name="Active" value="0">
                 <input type="checkbox" class="checkbox-box" name="Active" value ="1"  <?php echo ($results['users']->active) == "1"? "checked" : "" ?> />  

              </li>

                          

            </ul>
            
            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>
             

        </form>

     <?php if ($results['users']->userId) { ?>
          <p><a href="admin.php?action=deleteUser&amp;userId=<?php echo $results['users']->userId ?>" onclick="return confirm('Delete This User?')">
                  Delete This User
              </a>
          </p>
    <?php } ?>


    
	  
<?php include "templates/include/footer.php" ?>
