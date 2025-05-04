
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

if (isset($_POST['save'])) {
  $regId = $_SESSION['regId'];
  $studentName = $_SESSION['studentName'];
    $last_name = $_SESSION['lastName'];

    $class = $_POST['class'];
    $session = $_POST['session'];
    $amount = $_POST['amount'];
    $payment_type = $_POST['payment_type'];
    $month = $_POST['month'];
    $status = 'Pending';
    $dateCreated = date("Y-m-d H:i:s");

    $targetDir = "img/upload/";
    $originalFile = basename($_FILES["paymentImage"]["name"]);
    $fileName = time() . '_' . $originalFile;
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["paymentImage"]["tmp_name"], $targetFilePath)) {
            $query = mysqli_query($conn, "INSERT INTO payments 
            (reg_num, first_name, last_name, class, session, amount, payment_type, month, status, photo,created_at)
            VALUES 
            ('$regNum', '$first_name', '$last_name', '$class', '$session', '$amount', '$payment_type','$month', '$status', '$fileName', '$dateCreated')");

            if ($query) {
                $statusMsg = "<div class='alert alert-success'>Payment record created successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger'>Database insert failed!</div>";
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
            <h1 class="h3 mb-0 text-gray-800">Make Monthly Payment</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Make Monthly Payment</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Pay Monthly Fees</h6>
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
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" readonly value="<?php echo $_SESSION['studentName']; ?>">
                        </div>
           
                        <div class="col-xl-4">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" readonly value="<?php echo $_SESSION['lastName']; ?>">
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

                        <div class="col-xl-4">
                          <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                          <?php
                          $qry = "SELECT * FROM tblsessionterm ORDER BY sessionName ASC";
                          $result = $conn->query($qry);
                          $num = $result->num_rows;		
                          if ($num > 0){
                              echo '<select required name="session" class="form-control mb-3">';
                              echo '<option value="">--Select Session--</option>';
                              while ($rows = $result->fetch_assoc()){
                                  // Set the option value as className instead of Id
                                  echo '<option value="'.$rows['sessionName'].'">'.$rows['sessionName'].'</option>';
                              }
                              echo '</select>';
                          }
                          ?>  
                        </div>

                        <div class="col-xl-4">
                        <label class="form-control-label">Amount<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="amount" value="<?php echo $row['amount'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>


                    <div class="form-group row mb-3">
                        <div class="col-xl-4">
                        <label class="form-control-label">Paymemt Type<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="payment_type" readonly value="Monthly" id="exampleInputFirstName">
                        </div>
                    
                        <div class="col-xl-4">
                        <label for="month">Choose a month <span class="text-danger ml-2">*</span></label>
                          <select name="month" id="month" class="form-control mb-3">
                          <option value="">--Select month--</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                          </select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <img src="img/schoolQR.JPG" name="qr" style="height: 364px;width: 190px;"/>
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