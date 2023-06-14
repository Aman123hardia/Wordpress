<?php 
/** 
*Plugin Name: product1 plugin
* Plugin URI: localhost/wordpress
* Description: You add the product directly using this plugin
* Version: 1.0
* Author: Wordpress
* Author URI: localhost/wordpress
* 
 */

// create table product
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE If Not Exists`{$wpdb->base_prefix}custom_product` (
  id int NOT NULL,
  name varchar(250),
  product_image varchar(100),
  description varchar(5000),
  price int(10),
  shortcode varchar(200),
	PRIMARY KEY  (id)
) $charset_collate;";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

//Register plugin in admin menu
add_action('admin_menu','product_setup_register');
function product_setup_register(){
  
  // parent menu start
	$page_title = 'Product Plugin';
	$menu_title = 'Product Plugin';
	$capability = 'manage_options';
	$menu_slug  = 'product_manage';
	$callable   = 'product_list';

 	add_menu_page($page_title,$menu_title,$capability,$menu_slug,$callable);
  //parent menu end

 	//submenu start
	$parent_slug    = 'product_manage';
	$sub_page_title = 'Add_product';
	$sub_menu_title = 'Add_product';
	$sub_capability = 'manage_options';
	$sub_menu_slug  = 'add_product';
	$sub_callable   = 'add_product';
  
  add_submenu_page($parent_slug, 	$sub_page_title,	$sub_menu_title ,$sub_capability,$sub_menu_slug,$sub_callable );
	// submenu end
        	
  //submenu start
	$sub_page_title = 'Edit_product';
	$sub_menu_title = 'Edit_product';
	$sub_capability = 'manage_options';
	$sub_menu_slug  = 'edit_product';
	$sub_callable   = 'edit_product';
  
  add_submenu_page($parent_slug, $sub_page_title,	$sub_menu_title, $sub_capability, $sub_menu_slug, $sub_callable );
  //submenu end
}

// parent callable fuctio
function product_list(){
	global $wpdb;
	$table = $wpdb->prefix.'custom_product';
  $rows= $wpdb->get_results("SELECT * from $table");
  $profileData='';

  echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">  ';
  echo '

  <h1 class="text-center text-primary">All Product List</h1><hr>
        <div class="text-center">All Product shortcode is :- [allproduct_shortcode]</div><hr>
        <table class="table table-bordered" id="example"> <thead class="text-center text-info">
        <tr>
           <th scope="col">Product Id</th>
          <th scope="col">product</th>
          <th scope="col">product Name</th>
          <th scope="col">product description</th>
          <th scope="col">Product Price</th>
          <th scope="col">Shortcode</th>
          <th scope="col">Edit Product</th>
        </tr>
        </thead>
        <tbody class="text-center">';
	foreach($rows as $row){ 
    $profileData = '
     <tr><td>'.$row->id.'</td> <td class="w-25 text-center"><img src="http://localhost/wordpress/wp-content/uploads/2023/06/'.$row->product_image.'" alt="something is wrong" height="50%" width="52%" style="border-radius: 50%;"></td><td>'.$row->name.'</td><td>'.$row->description.'</td><td>'.$row->price.'</td><td>[product_sortcode id="'.$row->id.'"]</td><td><a href="     http://localhost/wordpress/wp-admin/admin.php?page=edit_product&id=' . $row->id . '">edit</a></td></tr>  

 ';

    echo $profileData;
  } 
}

//add submenu callable
function add_product(){
	?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

     <h1 class='text-center text-success'>Add Product</h1>
  <form action="" method="post" enctype="multipart/form-data">
       <div class="form-group pt-3">
        <label for="proId">Product Id:</label>
        <input type="number" class="form-control" placeholder="Enter your product id" name="proId">
      </div>
       <div class="form-group pt-2">
        <label for="productImage">Product Image</label>
         <input type="file" class="form-control" name="productImage">

      </div>
      <div class="form-group pt-3">
        <label for="fullname">Product Name:</label>
        <input type="text" class="form-control" placeholder="Enter your Product Name" name="fullname">
      </div>
      <div class="form-group pt-2">
        <label for="describtion">Product Describtion</label>
        <textarea class="form-control" name="describtion" placeholder="Describe Your Self" rows="3"></textarea>
      </div> 
      <div class="form-group pt-2">
        <label for="price">Product Price</label>
        <input type="number" class="form-control" placeholder="Enter your product price" name="price">
      </div> 

     <input type="submit" class="btn btn-success mt-3" name="SubmitButton" value="submit"/>
  </form>

  <?php 
}
// Add product  in database
if(isset($_POST) && $_POST['SubmitButton']=="submit"){
     global $wpdb;
    $table_name= $wpdb->prefix.'custom_product';
    $productImage=$_FILES['productImage']['name'];
    uploadProduct($productImage);
   $data = array('id' => $_POST["proId"],'product_image'=>$productImage,'name'=> $_POST['fullname'],'description'=>$_POST['describtion'],'price'=>$_POST['price'],'shortcode'=>'product_shortcode');
    $format = array( '%d','%s','%s','%s','%d','%s');
    $wpdb->insert($table_name,$data,$format); 
   ?><script>
    alert('Add Product Successfuly');
    </script><?php
    // header('location:http://localhost/wordpress/wp-admin/admin.php?page=add_product');
}

//edit submenu callable
function edit_product(){
  global $wpdb;
   $table = $wpdb->prefix.'custom_product';
  if (isset($_GET['id'])) 
    $id = $_GET['id'];
  $query = "SELECT * FROM $table WHERE `id` = '$id'";
    $rows = $wpdb->get_results($query);
  if(!empty($rows)){
  ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

     <h1 class='text-center text-success'>Update Product Details</h1>
 <form action="" method="post" enctype="multipart/form-data">
       <?php foreach ($rows as $row) {?>
        <div class="form-group pt-3 d-none">
        <label for="proId">Product Id:</label>
        <input type="number" class="form-control"  value="<?php echo $row->id; ?>" name="proId" >
      </div>

      <div class="mt-3 bg-light pt-2 show border">
      <label for="productImage" style='margin-right:3vw'>Upload Your Product:</label>
      <img src="http://localhost/wordpress/wp-content/uploads/2023/06/<?php echo $row->product_image; ?>" alt="hugenerd" width="70" height="70"  id='blah' class="rounded-circle" >
      <span class='' style='margin-left:5vw'>
      <label for="files" class="btn text-white bg-secondary">Change Product Image</label>
      <input type="file" class="form-control" name="productImage" style="visibility:hidden" onchange="readURL(this)" id="files">
   
        </span>
     </div>

      <div class="form-group mt-2">
        <label for="fullname">Product Name:</label>
        <input type="text" class="form-control" placeholder="Enter your Product Name" name="fullname" value="<?php echo $row->name; ?>">
      </div>
      <div class="form-group pt-2">
        <label for="describtion">Product Describtion</label>
        <textarea class="form-control" name="describtion" placeholder="Describe Your Self" rows="3" ><?php echo $row->product_image; ?></textarea>
      </div> 
      <div class="form-group d-none" >
        <label for="updateProduct">Update product:</label>
        <input type="text" class="form-control" placeholder="Enter your Product Name" name="updateProduct" value="<?php echo $row->product_image; ?>" id='productUpdate'>
      </div>
      <div class="form-group pt-2">
        <label for="price">Product Price</label>
        <input type="number" class="form-control" placeholder="Enter your product price" name="price" value="<?php echo $row->price; ?>">
      </div> 
       <?php
  }?>
     <input type="submit" class="btn btn-success mt-3" name="updateButton" value="Update" />
     <input type='submit' name="deleteButton" value='Delete' class="btn btn-success mt-3" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-trash"></i></input>
     <input type="submit" class="btn btn-success mt-3" name="Back" value="Back"/>
  </form>
  <script>
 function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
    document.getElementById('blah').src = e.target.result; 
    let filePath= document.getElementById('files').value;
    var fileName = filePath.replace(/^.*[\\\/]/, '');
      document.getElementById('productUpdate').value=fileName;

console.log(fileName);
  };

    reader.readAsDataURL(input.files[0]);
  }
  }
 </script>

  <?php 
 }
}

// update data in database
if(isset($_POST) && $_POST['updateButton']=="Update"){
  print_r($_POST); 
     global $wpdb;
    $table_name= $wpdb->prefix.'custom_product';
    $productImage=$_POST['updateProduct'];

    uploadProduct( $productImage);
    print_r($productImage); 
   $data = array('id' => $_POST["proId"],'product_image'=>$productImage,'name'=> $_POST['fullname'],'description'=>$_POST['describtion'],'price'=>$_POST['price'],'shortcode'=>'product_shortcode');
    
  $where = array('id' => $_POST["proId"]  );
  $wpdb->update( $table_name, $data, $where );
  ?><script>
    alert('Update Product Successfuly');
    </script><?php
    header('location:http://localhost/wordpress/wp-admin/admin.php?page=product_manage');
}

// delete product in database
if(isset($_POST) && $_POST['deleteButton']=="Delete"){
  print_r($_GET['id']);
     global $wpdb;
    $table_name= $wpdb->prefix.'custom_product';
    
    $delete_query = $wpdb->delete(
    $table_name, array('id' => $_GET['id'])
    );
  ?><script>
    alert('Delete Product Successfuly');
    </script><?php
    header('location:http://localhost/wordpress/wp-admin/admin.php?page=product_manage');
}

// delete product in database
if(isset($_POST) && $_POST['Back']=="Back"){
  header('location:http://localhost/wordpress/wp-admin/admin.php?page=product_manage');
}

// file upload
function uploadProduct($productImage){
  print_r($productImage); 
  $image_path = $_FILES['productImage']['tmp_name'];
  // Load WordPress environment
  require_once(ABSPATH . 'wp-load.php');

    // Check if the WordPress Media Upload functions are available
    if (function_exists('wp_upload_dir') && function_exists('media_handle_sideload')) {

        // Get the upload directory path and URL
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'];

        // Prepare the file array for the image upload
        $file_array = array(
            'name'     =>  $productImage,
            'tmp_name' => $image_path
        );

        // Upload the image file to the media directory
        $attachment_id = media_handle_sideload($file_array, 0);

        if (!is_wp_error($attachment_id)) {
            $message = "Image uploaded successfully!";
            echo $message;
        } else {
            // Display an error message if the upload fails
            $message = "Image upload failed: " . $attachment_id->get_error_message();
            echo $message;
        }
    } else {
        // Display an error message if the media handling functions are unavailable
        $message = "Media handling functions are not available.";
         echo $message;


    }  
}

// each product shortcode create
function product_shortcode($atts) {
 // Access the parameter value(s)
    $id = $atts['id'];
  // Generate the shortcode output
  global $wpdb;
  $table = $wpdb->prefix.'custom_product';
   $rows= $wpdb->get_results("SELECT * from $table where `id`=$id");
 $profileData='';
 echo   '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">';

  foreach($rows as $row){ 
  $profileData .= '<div class="card">
  <img src="http://localhost/wordpress/wp-content/uploads/2023/06/'.$row->product_image.'" style="width:100%">
  <h1>'.$row->name.'</h1>
  <p class="price">price'.$row->price.'</p>
  <p>'.$row->description.'</p>
  <p><a href="http://localhost/wordpress/wp-admin/post.php?post=251&action=edit">description</a></p>
  </div>';         
  } 
 return $profileData;
}
add_shortcode('product_sortcode', 'product_shortcode');


//every product shortcode
function all_products(){
  global $wpdb;
  $table = $wpdb->prefix.'custom_product';
  $rows= $wpdb->get_results("SELECT * from $table");
  $profileData='';
  echo   '<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container-fluid row justify-content-center mb-3 " >
  ';
   $profileData.= '<div class="search_box" >
    <form  action="" method="get" autocomplete="off">
        <input type="text" name="s" placeholder="Search Code..." id="keyword" class="input_search" onfocusout ="fetch()">
        <label class="bg-success text-white p-1"> Search </label>
        </input >
    </form>

</div> <div id="datafetch">';
  foreach($rows as $row){
  $profileData .= '

      <div class="col-md-12 col-xl-10" >
        <div class="card shadow-0 border rounded-3">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                <div class="bg-image hover-zoom ripple rounded ripple-surface">
            <img src="http://localhost/wordpress/wp-content/uploads/2023/06/'.$row->product_image.'"
                    class="w-100" />
                  <a href="#!">
                    <div class="hover-overlay">
                      <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <h5>'.$row->name.'</h5>
                <div class="d-flex flex-row">
                  <div class="text-danger mb-1 me-2">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </div>
                </div>
                <div class="mt-1 mb-0 text-muted small">
                  <span>100% Good Products</span>
                </div>
                <p class="text-truncate mb-4 mb-md-0">
                  There are many variations of passages of Lorem Ipsum available, but the
                  majority have suffered alteration in some form, by injected humour, or
                  randomised words which dont look even slightly believable.
                </p>
              </div>
              <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                <div class="d-flex flex-row align-items-center mb-1">
                  <h4 class="mb-1 me-1">'.$row->price.'</h4>
                  <span class="text-danger"><s>'.($row->price+20).'</s></span>
                </div>
                <h6 class="text-success">Free shipping</h6>
                <div class="d-flex flex-column mt-4">
                <a class="btn btn-primary btn-sm text-white"  href="http://localhost/wordpress/description-page/?id=' . $row->id . '">description</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>';         
  } 

 return $profileData. '</div></div>';
}
add_shortcode('allproduct_shortcode', 'all_products');