<?php
require('header.inc.php');
$categories_id = '';
$product_name = '';
$mrp = '';
$price = '';
$qty = '';
$image = '';
$short_description = '';
$description = '';
$meta_title = '';
$meta_description = '';
$meta_keyword = '';
$msg = '';
$name = '';
$image_rquired = 'required';
if (isset($_GET['id']) && $_GET['id'] != '') {
   $image_rquired = '';
   $id = get_safe_value($con, $_GET['id']);
   $res = mysqli_query($con, "select * from product where id='$id'");
   $check = mysqli_num_rows($res);
   if ($check > 0) {
      mysqli_query($con, "update product set categories_id='$categories_id',product_name='$product_name',
         mrp='$mrp',price='$price',qty='$qty',short_description='$short_description',
         description='$description',meta_title='$meta_title',meta_description='$meta_description',
         meta_keyword='$meta_keyword'
          where id='$id'");
      $row = mysqli_fetch_assoc($res);
      $categories_id = $row['categories_id'];
      $product_name = $row['product_name'];
      $mrp = $row['mrp'];
      $price = $row['price'];
      $qty = $row['qty'];
      $short_description = $row['short_description'];
      $description = $row['description'];
      $meta_title = $row['meta_title'];
      $meta_description = $row['meta_description'];
      $meta_keyword = $row['meta_keyword'];
   } else {
      header('location:product.php');
      die();
   }
}
if (isset($_POST['submit'])) {
   $categories_id = get_safe_value($con, $_POST['categories_id']);
   $product_name = get_safe_value($con, $_POST['product_name']);
   $mrp = get_safe_value($con, $_POST['mrp']);
   $price = get_safe_value($con, $_POST['price']);
   $qty = get_safe_value($con, $_POST['qty']);
   $short_description = get_safe_value($con, $_POST['short_description']);
   $description = get_safe_value($con, $_POST['description']);
   $meta_title = get_safe_value($con, $_POST['meta_title']);
   $meta_description = get_safe_value($con, $_POST['meta_description']);
   $meta_keyword = get_safe_value($con, $_POST['meta_keyword']);
   $res = mysqli_query($con, "select * from product where product_name='$product_name'");
   $check = mysqli_num_rows($res);
   if ($check > 0) {
      if (isset($_GET['id']) && $_GET['id'] != '') {
         $getData = mysqli_fetch_assoc($res);
         if ($id == $getData['id']) {
         } else {
            $msg = "Product Already Exist";
         }
      } else {
         $msg = "Product Already Exist";
      }
   }
   if (
      $_FILES['image']['type'] != 'image/jpeg'
      && $_FILES['image']['type'] != 'image/png'
      && $_FILES['image']['type'] != 'image/jpg'
   ) {
      $msg = "Only PNG,JPG and JPEG Image Format";
   }
   if ($msg == '') {
      if (isset($_GET['id']) && $_GET['id'] != '') {
         if ($_FILES['image']['name'] != '') {
            $image = rand(111111111, 99999999) . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], PRODUCT_IMAGE_SERVER_PATH . $image);
            $update_sql = "update product set categories_id='$categories_id',product_name='$product_name',
               mrp='$mrp',price='$price',qty='$qty',short_description='$short_description',image='$image',
               description='$description',meta_title='$meta_title',meta_description='$meta_description',
               meta_keyword='$meta_keyword'
                where id='$id'";
         } else {
            $update_sql = "update product set categories_id='$categories_id',product_name='$product_name',
            mrp='$mrp',price='$price',qty='$qty',short_description='$short_description',
            description='$description',meta_title='$meta_title',meta_description='$meta_description',
            meta_keyword='$meta_keyword'
             where id='$id'";
         }
         mysqli_query($con, $update_sql);
      } else {
         $image = rand(111111111, 99999999) . '_' . $_FILES['image']['name'];
         move_uploaded_file($_FILES['image']['tmp_name'], PRODUCT_IMAGE_SERVER_PATH . $image);
         mysqli_query($con, "insert into 
         product(categories_id,product_name,mrp,price,qty,short_description,image
         ,description,meta_title,meta_description,meta_keyword,status) values
         ('$categories_id','$product_name','$mrp','$price','$qty','$short_description','$image'
         ,'$description','$meta_title','$meta_description','$meta_keyword',1)");
      }
     
     
      /*Product Multiple Images Start*/
      
		if(isset($_GET['id']) && $_GET['id']!=''){
			foreach($_FILES['product_images']['name'] as $key=>$val){
				if($_FILES['product_images']['name'][$key]!=''){
					if(isset($_POST['product_images_id'][$key])){
						$image=rand(111111111,999999999).'_'.$_FILES['product_images']['name'][$key];
						move_uploaded_file($_FILES['product_images']['tmp_name'][$key],PRODUCT_MULTIPLE_IMAGE_SERVER_PATH.$image);
						mysqli_query($con,"update product_images set product_images='$image' where id='".$_POST['product_images_id'][$key]."'");
					}else{
						$image=rand(111111111,999999999).'_'.$_FILES['product_images']['name'][$key];
						move_uploaded_file($_FILES['product_images']['tmp_name'][$key],PRODUCT_MULTIPLE_IMAGE_SERVER_PATH.$image);
						mysqli_query($con,"insert into product_images(product_id,product_images) values('$id','$image')");
					}
					
				}
			}
		}else{
			if(isset($_FILES['product_images']['name'])){
				foreach($_FILES['product_images']['name'] as $key=>$val){
					if($_FILES['product_images']['name'][$key]!=''){
						$image=rand(111111111,999999999).'_'.$_FILES['product_images']['name'][$key];
						move_uploaded_file($_FILES['product_images']['tmp_name'][$key],PRODUCT_MULTIPLE_IMAGE_SERVER_PATH.$image);
						mysqli_query($con,"insert into product_images(product_id,product_images) values('$id','$image')");
					}
				}
			}
		}
		/*Product Multiple Images End*/
      header('location:product.php');
      die();
   }
}
?>
<div class="content pb-0">
   <div class="animated fadeIn">
      <div class="row">
         <div class="col-lg-12">
            <div class="card">
               <div class="card-header"><strong>Product</strong><small> Form</small></div>
               <form method="post" enctype="multipart/form-data">
                  <div class="card-body card-block">
                     <div class="form-group">
                        <label for="categories" class=" form-control-label">Categories</label>
                        <select class="form-control" name="categories_id">
                           <option>Select Category</option>
                           <?php
                           $res = mysqli_query($con, "select id,categories from categories
                                order by categories asc");
                           while ($row = mysqli_fetch_assoc($res)) {
                              if ($row['id'] == $categories_id) {
                                 echo "<option selected value=" . $row['id'] . ">" . $row['categories'] . "</option>";
                              } else {
                                 echo "<option value=" . $row['id'] . ">" . $row['categories'] . "</option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="form-group">
                        <label for="categories" class=" form-control-label">Product Name</label>
                        <input type="text" name="product_name" placeholder="Enter Product Name" class="form-control" required value="<?php echo $product_name ?>">
                     </div>
                     <div class="form-group">
                        <label for="categories" class=" form-control-label">MRP</label>
                        <input type="text" name="mrp" placeholder="Enter Product MRP" class="form-control" required value="<?php echo $mrp ?>">
                     </div>
                     <div class="form-group">
                        <label for="categories" class=" form-control-label">Price</label>
                        <input type="text" name="price" placeholder="Enter Product Price" class="form-control" required value="<?php echo $price ?>">
                     </div>
                     <div class="form-group">
                        <label for="categories" class=" form-control-label">Qty</label>
                        <input type="text" name="qty" placeholder="Enter Product Qty" class="form-control" required value="<?php echo $qty ?>">
                     </div>
                     <div class="form-group">
                        <div class="row" id="image_box">
                           <div class="col-lg-10">
                              <label for="categories" class=" form-control-label">Image</label>
                              <input type="file" name="image" placeholder="Enter Product Image" class="form-control" <?php echo $image_rquired ?>><br>
                              <?php
                              if ($image != '') {
                                 echo "<a target='_blank' href='" . PRODUCT_IMAGE_SITE_PATH . $image . "'><img width='150px' src='" . PRODUCT_IMAGE_SITE_PATH . $image . "'/></a>";
                              }
                              ?>
                           </div>
                           <div class="col-lg-2">
                              <label for="categories" class=" form-control-label"></label>
                              <button id="" type="button" class="btn btn-lg btn-info btn-block" onclick="add_more_images()">
                                 <span id="payment-button-amount">Add Image</span>
                              </button>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="categories" class=" form-control-label">Short Description</label>
                           <textarea name="short_description" placeholder=" Product Short Description" class="form-control" required value="<?php echo $short_description ?>"></textarea>
                        </div>
                        <div class="form-group">
                           <label for="categories" class=" form-control-label">Product Description</label>
                           <textarea name="description" placeholder="Description" class="form-control" required value="<?php echo $description ?>"></textarea>
                        </div>
                        <div class="form-group">
                           <label for="categories" class=" form-control-label">Meta Title</label>
                           <textarea name="meta_title" placeholder="Meta Title" class="form-control" value="<?php echo $meta_title ?>"></textarea>
                        </div>
                        <div class="form-group">
                           <label for="categories" class=" form-control-label">Meta Description</label>
                           <textarea name="meta_description" placeholder="Meta Description" class="form-control" value="<?php echo $meta_description ?>"></textarea>
                        </div>
                        <div class="form-group">
                           <label for="categories" class=" form-control-label">Meta Keyword</label>
                           <textarea name="meta_keyword" placeholder="Meta Keyword" class="form-control" value="<?php echo $meta_keyword ?>"></textarea>
                        </div>
                        <button id="payment-button" name="submit" type="submit" class="btn btn-lg btn-info btn-block">
                           <span id="payment-button-amount">Submit</span>
                        </button>
                        <div class="field_error"><?php echo $msg ?></div>
                     </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   var total_image = 1;

   function add_more_images() {
      total_image++;
      var html = '<div class="col-lg-6" style="margin-top:20px;" id="add_image_box_' + total_image + '"><label for="categories" class=" form-control-label">Image</label><input type="file" name="product_images[]" class="form-control" required><button type="button" class="btn btn-lg btn-danger btn-block" onclick=remove_image("' + total_image + '")><span id="payment-button-amount">Remove</span></button></div>';
      jQuery('#image_box').append(html);
   }

   function remove_image(id) {
      jQuery('#add_image_box_' + id).remove();
   }
</script>
<?php
require('footer.inc.php');
?>