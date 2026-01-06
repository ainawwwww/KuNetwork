<?php include 'db.php';
include 'check_login.php';
// Get product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Fetch product information
$product_query = $conn->query("SELECT * FROM products WHERE id_p = ".$_GET['id']);
$product = $product_query->fetch_assoc();

// Fetch product images
$image_query = $conn->query("SELECT * FROM product_images WHERE product_id = ".$_GET['id']);
// Handle image deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_image_query = $conn->query("SELECT image FROM product_images WHERE id = $delete_id");
    $image_data = $delete_image_query->fetch_assoc();
    
    $image_path = "images/" . $image_data['image'];

        // Check if file exists
        if (file_exists($image_path)) {
            if ($image_data && unlink("images/".$image_data['image'])) {
                $conn->query("DELETE FROM product_images WHERE id = $delete_id");
            }
        }
        else{
            $conn->query("DELETE FROM product_images WHERE id = $delete_id");
        }
    
    
    
    
    echo "<script>
        window.location='product_images.php?id=$product_id';
        </script>"; 
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image'])) {
    //image upload start
    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif']; // Allowed formats
    $maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
    $maxWidth = 1920; // Maximum width
    $maxHeight = 1080; // Maximum height

    $fileName = $_FILES['new_image']['name'];

            $fileTmpName = $_FILES['new_image']['tmp_name'];
            $fileSize = $_FILES['new_image']['size'];
            $fileType = $_FILES['new_image']['type'];
            $fileError = $_FILES['new_image']['error'];
            $uploadStatus=1;

            // Validate for upload errors
            if ($fileError !== UPLOAD_ERR_OK) {
                echo "Error uploading file: $fileName. Error code: $fileError<br>";
$uploadStatus=0;                    
                
            }

            // Validate file size
            if ($fileSize > $maxFileSize) {
                echo "File too large: $fileName exceeds 5 MB.<br>";
                $uploadStatus=0; 
               
            }

            // Validate file format
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedFormats)) {
                
                echo "Invalid file format: $fileName is not an allowed image type.<br>";
                $uploadStatus=0;                     
              
            }

            // Validate image dimensions
            list($width, $height) = getimagesize($fileTmpName);
            if ($width > $maxWidth || $height > $maxHeight) {
                echo "Image dimensions too large: $fileName ($width x $height). Max allowed: 1920 x 1080.<br>";
$uploadStatus=0; 
                
            }

            // Generate a unique name and move the file
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $uploadDir = 'images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create directory if not exists
            }


            if($uploadStatus==1 )
            {
                         $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                         // Generate a unique name and move the file
                         $newFileName = uniqid('img_', true) . '.' . $fileExtension;
                         $uploadDir = 'images/';
                         $uploadPath = $uploadDir . $newFileName;
                         if (move_uploaded_file($fileTmpName, $uploadPath)) {
     
                             $upload_query="INSERT INTO product_images (product_id, image) VALUES ($product_id, '$newFileName')";
                             mysqli_query($conn,$upload_query);   
                             echo "<script>
                             alert('Image Has Been Uploaded Succesfully');
                             window.location='product_images.php?id=$product_id';
                             </script>"; 
                         }
                         else
                         {
                             echo "Error moving file: $fileName<br>";
                             
                         }
     
     }
            

            
            
        }


  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Gallery</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="plugins/ekko-lightbox/ekko-lightbox.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include 'sidebar.php'; ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Images for Product: <?= htmlspecialchars($product['product_name']) ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Gallery</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-primary">
              <div class="card-header">
                <h4 class="card-title">Add New Image</h4>
              </div>
              <div class="card-body">
                <div>
                  <!-- Form to upload a new image -->
                  <form method="POST" enctype="multipart/form-data" class="mb-4">
                        <div class="input-group">
                            <input type="file" name="new_image" class="form-control" required>
                            <button class="btn btn-primary" type="submit">Upload Image</button>
                        </div>
                    </form>
                  
                </div>
                <div>
                  <!-- <div class="filter-container p-0 row">
                    <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                      <a href="https://via.placeholder.com/1200/FFFFFF.png?text=1" data-toggle="lightbox" data-title="sample 1 - white">
                        <img src="https://via.placeholder.com/300/FFFFFF?text=1" class="img-fluid mb-2" alt="white sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
                      <a href="https://via.placeholder.com/1200/000000.png?text=2" data-toggle="lightbox" data-title="sample 2 - black">
                        <img src="https://via.placeholder.com/300/000000?text=2" class="img-fluid mb-2" alt="black sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="3, 4" data-sort="red sample">
                      <a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=3" data-toggle="lightbox" data-title="sample 3 - red">
                        <img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=3" class="img-fluid mb-2" alt="red sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="3, 4" data-sort="red sample">
                      <a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=4" data-toggle="lightbox" data-title="sample 4 - red">
                        <img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=4" class="img-fluid mb-2" alt="red sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
                      <a href="https://via.placeholder.com/1200/000000.png?text=5" data-toggle="lightbox" data-title="sample 5 - black">
                        <img src="https://via.placeholder.com/300/000000?text=5" class="img-fluid mb-2" alt="black sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                      <a href="https://via.placeholder.com/1200/FFFFFF.png?text=6" data-toggle="lightbox" data-title="sample 6 - white">
                        <img src="https://via.placeholder.com/300/FFFFFF?text=6" class="img-fluid mb-2" alt="white sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                      <a href="https://via.placeholder.com/1200/FFFFFF.png?text=7" data-toggle="lightbox" data-title="sample 7 - white">
                        <img src="https://via.placeholder.com/300/FFFFFF?text=7" class="img-fluid mb-2" alt="white sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
                      <a href="https://via.placeholder.com/1200/000000.png?text=8" data-toggle="lightbox" data-title="sample 8 - black">
                        <img src="https://via.placeholder.com/300/000000?text=8" class="img-fluid mb-2" alt="black sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="3, 4" data-sort="red sample">
                      <a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=9" data-toggle="lightbox" data-title="sample 9 - red">
                        <img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=9" class="img-fluid mb-2" alt="red sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                      <a href="https://via.placeholder.com/1200/FFFFFF.png?text=10" data-toggle="lightbox" data-title="sample 10 - white">
                        <img src="https://via.placeholder.com/300/FFFFFF?text=10" class="img-fluid mb-2" alt="white sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                      <a href="https://via.placeholder.com/1200/FFFFFF.png?text=11" data-toggle="lightbox" data-title="sample 11 - white">
                        <img src="https://via.placeholder.com/300/FFFFFF?text=11" class="img-fluid mb-2" alt="white sample"/>
                      </a>
                    </div>
                    <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
                      <a href="https://via.placeholder.com/1200/000000.png?text=12" data-toggle="lightbox" data-title="sample 12 - black">
                        <img src="https://via.placeholder.com/300/000000?text=12" class="img-fluid mb-2" alt="black sample"/>
                      </a>
                    </div>
                  </div> -->
                </div>

              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card card-primary">
              <div class="card-header">
                <h4 class="card-title">Image Gallery</h4>
              </div>
              <div class="card-body">
                <div class="row">
                <?php while ($image = $image_query->fetch_assoc()): ?>
                  <div class="col-sm-2">
                    <div class="card mb-4">
                        <img src="images/<?= htmlspecialchars($image['image']) ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body text-center">
                            <a href="?id=<?= $_GET['id'] ?>&delete_id=<?= $image['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this image?')">
                            Delete
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                  
                  
                  
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Ekko Lightbox -->
<script src="plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Filterizr-->
<script src="plugins/filterizr/jquery.filterizr.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({gutterPixels: 3});
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })
</script>
</body>
</html>
