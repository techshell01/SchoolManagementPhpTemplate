
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

if (isset($_POST['save'])) {
    $regId = $_SESSION['regId'];
    $studentName = $_SESSION['studentName'];
    // $last_name = $_SESSION['lastName'];

    $class = $_POST['class'];
    $session = $_POST['session'];
    $amount = $_POST['amount'];
    $payment_type = $_POST['payment_type'];
    $month = $_POST['month'];
    $payment_mode = $_POST['payment_mode'];
    $status = 'Pending';
    $dateCreated = date("Y-m-d H:i:s");





 //Generate unique payment_id
 $payment_id = 'PAY' . uniqid();

    $targetDir = "img/upload/";
    $originalFile = basename($_FILES["paymentImage"]["name"]);
    $fileName = time() . '_' . $originalFile;
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["paymentImage"]["tmp_name"], $targetFilePath)) {
            $sql = "INSERT INTO payments 
            (payment_id,regId, studentName, class, session, amount, payment_type, status, payment_mode, photo, created_at)
            VALUES 
            ('$payment_id','$regId', '$studentName', '$class', '$session', '$amount', '$payment_type', '$status', '$payment_mode', '$fileName', '$dateCreated')";
            $query = mysqli_query($conn, $sql);
            // echo $sql;
            // die();
            if ($query) {
                $statusMsg = "<div class='alert alert-success'>Payment record created successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger'>Database insert failed!</div>";
                // $statusMsg = "<div class='alert alert-danger'>Database insert failed! Error: " . mysqli_error($conn) . "</div>";

            }
        } else {
            $statusMsg = "<div class='alert alert-danger'>File upload failed!</div>";
        }
    } else {
        $statusMsg = "<div class='alert alert-danger'>Only JPG, JPEG, PNG, and PDF files are allowed!</div>";
    }
}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $Id= $_GET['Id'];

        $query=mysqli_query($conn,"select * from tblstudents where Id ='$Id'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

        if(isset($_POST['update'])){
    
             $firstName=$_POST['firstName'];
  $lastName=$_POST['lastName'];
  $otherName=$_POST['otherName'];

  $admissionNumber=$_POST['admissionNumber'];
  $classId=$_POST['classId'];
  $classArmId=$_POST['classArmId'];
  $dateCreated = date("Y-m-d");

 $query=mysqli_query($conn,"update tblstudents set firstName='$firstName', lastName='$lastName',
    otherName='$otherName', admissionNumber='$admissionNumber',password='12345', classId='$classId',classArmId='$classArmId'
    where Id='$Id'");
            if ($query) {
                
                echo "<script type = \"text/javascript\">
                window.location = (\"createStudents.php\")
                </script>"; 
            }
            else
            {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
        }
    }


//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];
        $classArmId= $_GET['classArmId'];

        $query = mysqli_query($conn,"DELETE FROM tblstudents WHERE Id='$Id'");

        if ($query == TRUE) {

            echo "<script type = \"text/javascript\">
            window.location = (\"createStudents.php\")
            </script>";
        }
        else{

            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
         }
      
  }
  $product_name = "Check Shirts";
  $sizes = [20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44];
  $quantities = [222, 234, 247, 260, 273, 291, 309, 330, 340, 355, 0, 0, 0]; // sample quantities

  // Insert product
  $stmt = $conn->prepare("INSERT INTO products (product_name) VALUES (?)");
  $stmt->bind_param("s", $product_name);
  $stmt->execute();
  $product_id = $stmt->insert_id;
  $stmt->close();

  // Insert size-wise quantities
  $stmt2 = $conn->prepare("INSERT INTO product_sizes (product_id, size, quantity) VALUES (?, ?, ?)");
  for ($i = 0; $i < count($sizes); $i++) {
      $stmt2->bind_param("iii", $product_id, $sizes[$i], $quantities[$i]);
      $stmt2->execute();
  }
  $stmt2->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
<?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">



   <script>
    function classArmDropdown(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
}
</script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Make Admission Payment</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Make Admission Payment</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Pay Admission Fees</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                  <div class="form-group row mb-3">
                  <div class="col-xl-8">
                   <div class="form-group row mb-3">
                   <div class="col-xl-4">
                        <label class="form-control-label">Reg Number<span class="text-danger ml-2">*</span></label>
                      
                        <input type="text" class="form-control" readonly value="<?php echo $_SESSION['regId']; ?>">
                        </div>

                        <div class="col-xl-4">
                        <label class="form-control-label">Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" readonly value="<?php echo $_SESSION['studentName']; ?>">
                        </div>

                        <div class="col-xl-4">
                        <label class="form-control-label">Payment Type<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="payment_type" readonly value="Admission" id="exampleInputFirstName">
                      </div>
                     
          
                    </div>
                   
                     <div class="form-group row mb-3">
                        <!-- <div class="col-xl-4">
                        <label class="form-control-label">Class<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="class" value="<?php echo $row['class'];?>" id="exampleInputFirstName" >
                        </div> -->
                        
                        <div class="col-xl-4">
                          <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                          <?php
                          $qry = "SELECT * FROM tblclass ORDER BY className ASC";
                          $result = $conn->query($qry);
                          $num = $result->num_rows;		
                          if ($num > 0){
                              echo '<select required name="class" class="form-control mb-3">';
                              echo '<option value="">--Select Class--</option>';
                              while ($rows = $result->fetch_assoc()){
                                  // Set the option value as className instead of Id
                                  echo '<option value="'.$rows['className'].'">'.$rows['className'].'</option>';
                              }
                              echo '</select>';
                          }
                          ?>  
                        </div>



                        <!-- <div class="col-xl-4">
                        <label class="form-control-label">Session<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" required name="session" value="<?php echo $row['session'];?>" id="exampleInputFirstName" >
                        </div> -->
                        <div class="form-group">
                        <label class="font-weight" for="payment_mode" style="margin-left:10px" >Payment Mode<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" id="payment_mode" name="payment_mode" required style="width: 35vh; max-width: 350px; padding: 10px ; margin-left:10px">
                          <option value="">-- Select Payment Mode --</option>
                          <option value="UPI/QR"> UPI/QR</option>
                          <option value="Account Transation"> Account Transation</option>
                          <option value="Cash"> Cash</option>
                        </select>
                        </div>
                       
                        <div class="col-xl-4">
                        <label class="form-control-label" style="margin-left:10px">Amount<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="amount" style="margin-left:10px" value="<?php echo $row['amount'];?>" id="exampleInputFirstName" >
                        </div>

                        <div class="col-xl-4">
                        <label class="form-control-label">Bank Name<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="bank name" readonly value="Punjab National Bank" id="exampleInputFirstName" >
                        </div>

                        <div class="col-xl-4">
                        <label class="form-control-label">Account Number<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="ac number" readonly value="0564056000010" id="exampleInputFirstName" >
                        </div>

                        <div class="col-xl-4">
                        <label class="form-control-label">RTGS/NEFT IFSC Code<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="ifc code" readonly value="PUNB0056420" id="exampleInputFirstName" >
                        </div>
                    <!-- Torso Wear    -->
                        <?php
	$torsoData = [];
	$result = $conn->query("SELECT * FROM torso_wear ORDER BY uniform_type, size");

	while ($row = $result->fetch_assoc()) {
		$type = $row['uniform_type'];
		$size = $row['size'];
		$price =(int) $row['price'];
		$torsoData[$type][$size] = $price;
	}
  
	?>

<!-- Torso Wear Dropdown -->
<div class="row">
    <div class="col-md-4">
        <label style="margin-left:10px;margin-top:15px">Torso Wear</label>
        <select class="form-control" id="uniform_type" name="uniform_type" onchange="populateSizes()"
        style="width: 35vh; max-width: 350px; padding: 10px ; margin-left:10px">
            <option value="">-- Select Uniform --</option>
            <?php foreach ($torsoData as $type => $sizes): ?>
                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Size Dropdown -->
    <div class="col-md-4">
        <label style="margin-left:5px; margin-top:15px">Size</label>
        <select class="form-control" id="uniform_size" name="uniform_size" onchange="updatePrice()"
        style="width: 255px; max-width: 350px; padding: 10px ; margin-left:2px;">
            <option value="">-- Select Size --</option>
        </select>
    </div>

    <!-- Price Display -->
    <div class="col-md-4">
        <label style="margin-top:15px">Price</label>
        <input type="text" class="form-control" id="uniform_price" name="uniform_price" readonly placeholder="Auto-filled"
       style="width: 34vh; max-width: 350px; padding: 10px ; margin-right:2px">
    </div>
</div>

<script>
    const torsoData = <?= json_encode($torsoData); ?>;

    function populateSizes() {
        const type = document.getElementById('uniform_type').value;
        const sizeDropdown = document.getElementById('uniform_size');
        const priceInput = document.getElementById('uniform_price');

        // Reset
        sizeDropdown.innerHTML = '<option value="">-- Select Size --</option>';
        priceInput.value = '';

        if (torsoData[type]) {
            for (const size in torsoData[type]) {
                const opt = document.createElement('option');
                opt.value = size;
                opt.text = size;
                sizeDropdown.appendChild(opt);
            }
        }
    }

    function updatePrice() {
        const type = document.getElementById('uniform_type').value;
        const size = document.getElementById('uniform_size').value;
        const price = torsoData[type] && torsoData[type][size] ? torsoData[type][size] : '';
        document.getElementById('uniform_price').value = price;
    }
</script>


<!-- Buttom Wear -->
<?php
$bottomData = [];
$result = $conn->query("SELECT * FROM bottom_wear ORDER BY uniform_type, size");

while ($row = $result->fetch_assoc()) {
    $type = $row['uniform_type'];
    $size = $row['size'];
    $price = $row['price'];
    $bottomData[$type][$size] = $price;
}
?>
<!-- Bottom Wear Dropdown -->
<div class="row">
    <div class="col-md-4">
        <label style="margin-left:10px;margin-top:15px">Bottom Wear</label>
        <select class="form-control" id="bottom_uniform_type" name="bottom_uniform_type" onchange="populateBottomSizes()"
        style="width: 35vh; max-width: 350px; padding: 10px ; margin-left:10px">
            <option value="">-- Select Uniform --</option>
            <?php foreach ($bottomData as $type => $sizes): ?>
                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Size Dropdown -->
    <div class="col-md-4">
        <label style="margin-left:5px; margin-top:15px">Size</label>
        <select class="form-control" id="bottom_uniform_size" name="bottom_uniform_size" onchange="updateBottomPrice()"
        style="width: 255px; max-width: 350px; padding: 10px ; margin-left:2px;">
            <option value="">-- Select Size --</option>
        </select>
    </div>

    <!-- Price Display -->
    <div class="col-md-4">
        <label style="margin-top:15px">Price</label>
        <input type="text" class="form-control" id="bottom_uniform_price" name="bottom_uniform_price" readonly placeholder="Auto-filled"
        style="width: 34vh; max-width: 350px; padding: 10px ; margin-right:2px">
    </div>
</div>
<script>
    const bottomData = <?= json_encode($bottomData); ?>;

    function populateBottomSizes() {
        const type = document.getElementById('bottom_uniform_type').value;
        const sizeDropdown = document.getElementById('bottom_uniform_size');
        const priceInput = document.getElementById('bottom_uniform_price');

        sizeDropdown.innerHTML = '<option value="">-- Select Size --</option>';
        priceInput.value = '';

        if (bottomData[type]) {
            for (const size in bottomData[type]) {
                const opt = document.createElement('option');
                opt.value = size;
                opt.text = size;
                sizeDropdown.appendChild(opt);
            }
        }
    }

    function updateBottomPrice() {
        const type = document.getElementById('bottom_uniform_type').value;
        const size = document.getElementById('bottom_uniform_size').value;
        const price = bottomData[type] && bottomData[type][size] ? bottomData[type][size] : '';
        document.getElementById('bottom_uniform_price').value = price;
    }
</script>
<?php
// Tie Data
$tieData = [];
$result = $conn->query("SELECT * FROM tie_wear ORDER BY size");
while ($row = $result->fetch_assoc()) {
    $tieData[$row['size']] = $row['price'];
}

// Belt Data
$beltData = [];
$result = $conn->query("SELECT * FROM belt_wear ORDER BY size");
while ($row = $result->fetch_assoc()) {
    $beltData[$row['size']] = $row['price'];
}

// Socks Data
$socksData = [];
$result = $conn->query("SELECT * FROM socks_wear ORDER BY size");
while ($row = $result->fetch_assoc()) {
    $socksData[$row['size']] = $row['price'];
}
?>
<!-- Tie -->
<div class="row">
    <!-- Tie Size Dropdown -->
    <div class="col-md-6">
        <label style="margin-left:10px; margin-top:15px;">Tie</label>
        <select class="form-control" id="tie_size" onchange="updateTiePrice()"
            style="width: 100%; padding: 10px; margin-left:10px;">
            <option value="">-- Select Tie Size --</option>
            <?php foreach ($tieData as $size => $price): ?>
                <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Tie Price Display -->
    <div class="col-md-6">
        <label style="margin-top:15px;">Price</label>
        <input type="text" class="form-control" id="tie_price" readonly placeholder="Auto-filled"
            style="width: 100%; padding: 10px; margin-right:128px;">
    </div>
</div>


<!-- Belt -->
<div class="row">
    <div class="col-md-6">
        <label style="margin-left:10px; margin-top:15px;">Belt</label>
        <select class="form-control" id="belt_size" onchange="updateBeltPrice()"
            style="width: 100%; padding: 10px; margin-left:10px;">
            <option value="">-- Select Belt Size --</option>
            <?php foreach ($beltData as $size => $price): ?>
                <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label style="margin-top:15px;">Price</label>
        <input type="text" class="form-control" id="belt_price" readonly placeholder="Auto-filled"
            style="width: 100%; padding: 10px;margin-right:128px;">
    </div>
</div>


<!-- Socks -->
<div class="row">
    <div class="col-md-6">
        <label style="margin-left:10px; margin-top:15px;">Socks</label>
        <select class="form-control" id="socks_size" onchange="updateSocksPrice()"
            style="width: 100%; padding: 10px; margin-left:10px;">
            <option value="">-- Select Socks Size --</option>
            <?php foreach ($socksData as $size => $price): ?>
                <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label style="margin-top:15px;">Price</label>
        <input type="text" class="form-control" id="socks_price" readonly placeholder="Auto-filled"
            style="width: 100%; padding: 10px;margin-right:118px;">
    </div>
</div>

<script>
    const tieData = <?= json_encode($tieData); ?>;
    const beltData = <?= json_encode($beltData); ?>;
    const socksData = <?= json_encode($socksData); ?>;

    function updateTiePrice() {
        const size = document.getElementById('tie_size').value;
        document.getElementById('tie_price').value = tieData[size] ?? '';
    }

    function updateBeltPrice() {
        const size = document.getElementById('belt_size').value;
        document.getElementById('belt_price').value = beltData[size] ?? '';
    }

    function updateSocksPrice() {
        const size = document.getElementById('socks_size').value;
        document.getElementById('socks_price').value = socksData[size] ?? '';
    }
</script>



                      </div>
                  </div>
                  <div class="col-xl-4">
                      <img src="img/schoolQR.JPG" name="qr" style="height: 452px;width: 260px;"/>
                      </div>
                  </div>
                <div class="form-group row mb-3">
                <div class="col-xl-4"> <label class="form-control-label">Upload Payment receipt<span class="text-danger ml-2">*</span></label>
                <input type="file"  name="paymentImage" onChange="displayImage(this)" id="paymentImage" class="form-control"  class="form-control" required></div><div class="col-xl-4"><img src="img/logo/attnlg.jpg" onClick="triggerClick()" id="paymentDisplay"></div>
                </div>
            
                      <button type="submit" name="save" class="btn btn-primary">Save</button>
                    
                    </form>
                  </div>
                </div>

                <!-- Input Group -->
              
              </div>
            </div>
            <!--Row-->

          </div>
          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include "Includes/footer.php";?>
        <!-- Footer -->
      </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });

      //display image
      function triggerClick(e) {
    document.querySelector('#paymentImage').click();
  }
  function displayImage(e) {
    if (e.files[0]) {
      //alert("hhhhhhhhhhhhh")
      var reader = new FileReader();
      reader.onload = function(e){
        document.querySelector('#paymentDisplay').setAttribute('src', e.target.result);
      }
      reader.readAsDataURL(e.files[0]);
    }
  }
    </script>
  </body>

  </html>               