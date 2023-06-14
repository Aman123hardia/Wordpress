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

// callable
function product_list(){
	global $wpdb;
	$table = $wpdb->prefix.'custom_product';
   $rows= $wpdb->get_results("SELECT * from $table");
 $profileData='';

  echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">';
  echo '<table class="table table-bordered"> <thead class="text-center">
        <tr>
          <th scope="col">product</th>
          <th scope="col">Id</th>
          <th scope="col">product Name</th>
          <th scope="col">product description</th>
          <th scope="col">Product Price</th>
        </tr>
        </thead>
        <tbody class="text-center">';
	foreach($rows as $row){ 
          $profileData = '
          <tr><td>'.$row->id.'</td> <td class="w-25 text-center"><img src="http://localhost/wordpress/wp-content/uploads/2023/06/'.$row->product_image.'" alt="something is wrong" height="50%" width="52%" style="border-radius: 50%;"></td><td>'.$row->description.'</td><td>'.$row->price.'</td><td>'.$row->price.'</td><td><td>[product_sortcode id="'.$row->id.'"]</td></tr>';
           echo $profileData;
                    } 

              echo '<table> <tbody>
              <form method="POST" enctype="multipart/form-data">
  <input type="file" name="image" />
  <input type="submit" value="Upload" />
</form>


              ';

	}



//add submenu callable
function add_product(){
	?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

     <h1 class='text-center text-dark'>Add Product</h1>
  <form action="" method="post">
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



//edit submenu callable


function edit_product(){
	echo 'edit product callable'; ?>
<!-- HTML form to upload the image -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*">
    <input type="submit" name="submit" value="Upload">
</form>
<?php

}


if ( isset( $_POST['submit'] ) ) {
    $uploaded_image = $_FILES['image'];
    echo 'Image upload run';

    // Check if an image is uploaded
    if ( $uploaded_image['name'] ) {
        $upload_dir       = wp_upload_dir();
        $uploaded_file    = $uploaded_image['tmp_name'];
        $uploaded_file    = wp_check_filetype_and_ext( $uploaded_file, $uploaded_image['name'] );
        $uploaded_file    = move_uploaded_file( $uploaded_image['tmp_name'], $upload_dir['path'] . '/' . $uploaded_image['name'] );
        $uploaded_file    = $upload_dir['url'] . '/' . $uploaded_image['name'];

        // Display the uploaded image
        echo '<img src="' . $uploaded_file . '" alt="Uploaded Image" />';

        // You can perform additional actions with the uploaded file here
    }
}


if(isset($_POST) && $_POST['SubmitButton']=="submit"){
  print_r($_POST); die();
    global $wpdb;
    $table_name= $wpdb->prefix.'custom_product';
    $data = array('id' => $_POST["proId"],'product_image'=>$_POST['productImage'],'name'=> $_POST['fullname'],'description'=>$_POST['describtion'],'price'=>$_POST['price'],'shortcode'=>'product_shortcode');
    $format = array( '%d','%s','%s','%s','%d','%s');
    $wpdb->insert($table_name,$data,$format); 
  $message = "product Detail successfully submited";
    echo $message;
}



function product_shortcode($atts) {
 // Access the parameter value(s)
    $id = $atts['id'];

    // Generate the shortcode output
      global $wpdb;
  $table = $wpdb->prefix.'custom_product';
   $rows= $wpdb->get_results("SELECT * from $table where `id`=$id");
 $profileData='';

  foreach($rows as $row){ 
          $profileData .= '<div class="card">
  <img src="http://localhost/wordpress/wp-content/uploads/2023/06/'.$row->product_image.'" style="width:100%">
  <h1>'.$row->name.'</h1>
  <p class="price">price'.$row->price.'</p>
  <p>'.$row->description.'</p>
  <p><button>Add to Cart</button></p>
</div>';
          
      
  } 


    return $profileData;
}

add_shortcode('product_sortcode', 'product_shortcode');











