<?php include "templates/include/headerUsers.php" ?>
<?php include "templates/admin/include/headerUsers.php" ?>

 <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

<h1>All users</h1>

<table>
            <tr>
              <th>Users</th>
              <th>Password</th>
              <th>Active</th>
              
            </tr>

<?php foreach ( $results['users'] as $users ) { ?>

              <tr onclick="location='admin.php?action=editUser&amp;userId=<?php echo $users->userId?>'">
              <td>
                <?php echo $users->userName?>
              </td>
              <td>
                <?php echo $users->password?>
              </td>
              
              <td> 
                <?php 
                 if ($users ->active == '1'){
                  echo "ДА";
                 } else
                  {
                    echo "НЕТ";
                  }?>
                  
               
              </td>

            </tr>

    <?php } ?>
</table>

    <p><?php echo $results['totalRows']?> user<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>


    <p><a href="admin.php?action=newUser">Add a New User</a></p>

    <p><a href="admin.php?">Articles</a></p>

           