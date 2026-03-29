<?php include "templates/include/header.php" ?>
	<?php include "templates/admin/include/header.php" ?>
	  
            <h1>Article SubCategories</h1>
	  
	<?php if ( isset( $results['errorMessage'] ) ) { ?>
	        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>
	  
	  
	<?php if ( isset( $results['statusMessage'] ) ) { ?>
	        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
	<?php } ?>
	  
            <table>
                <tr>
                    <th>Category</th> 
                    <th>SubCategory</th>
                </tr>

        <?php foreach ( $results['subcategories'] as $subcategory ) { ?>

                <tr onclick="location='admin.php?action=editSubCategory&amp;subcategoryId=<?php echo $subcategory->id?>'">
                 <td>
                        <?php echo $subcategory->categories_name?>
                 </td>
                
                 <td>
                        <?php echo $subcategory->name?>
                    </td>
                </tr>

        <?php } ?>

            </table>

            <p><?php echo $results['totalRows']?> subcategor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>

            <p><a href="admin.php?action=newSubCategory">Add a New SubCategory</a></p>
	  
	<?php include "templates/include/footer.php" ?>
