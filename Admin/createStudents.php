
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';


//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
  // Fetch form values
  $regId = $_POST['regId'];
    // Check for duplicates
    $query = mysqli_query($conn, "SELECT max(regId) as maxRegId FROM tblstudents");
    $regVal = mysqli_fetch_array($query);
    $regIdVal = $regVal[0] +  1;
    echo $regIdVal;
    //die();

  //echo $regId;
  
  $studentName = $_POST['studentName'];
  $classId = $_POST['classId'];
  $classSecId = $_POST['classSecId'];
  $fatherName = $_POST['fatherName'];
  $motherName = $_POST['motherName'];
  $priPhoneNo = $_POST['priPhoneNo'];
  $secPhoneNo = $_POST['secPhoneNo'];
  $address = $_POST['address'];
  $zone = $_POST['zone'];
  $secLang = $_POST['secLang'];
  $dob = $_POST['dob'];
  $commute = $_POST['commute'];
  $dateCreated = date("Y-m-d");

  // Check for duplicates
  $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE regId = '$regIdVal'");
  $ret = mysqli_fetch_array($query);

  if ($ret > 0) {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Registration ID Already Exists!</div>";
  } else {
      // Handle student photo upload
      $studentPhoto = '';
      if (!empty($_FILES['studentPhoto']['name'])) {
          $targetDir = "Student/studentPhoto/";
          $fileName = basename($_FILES["studentPhoto"]["name"]);
          echo $fileName; 
          $targetDir = "studentPhoto/";
          $targetFilePath = $targetDir.$regIdVal."_".$fileName;
          echo $targetFilePath;
      
          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

          // Allow only specific file formats
          $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
          if (in_array(strtolower($fileType), $allowTypes)) {
              if (move_uploaded_file($_FILES["studentPhoto"]["tmp_name"], $targetFilePath)) {
                  $studentPhoto = $targetFilePath;
              }
          }
      }
// echo $regIdVal."<br>";
//die();
      $defaultPassword = md5('12345');
      $sql = "INSERT INTO tblstudents( 
    `regId`, `studentName`, `classId`, `classSecId`, `fatherName`, `motherName`, 
    `priPhoneNo`, `secPhoneNo`, `address`, `zone`, `secLang`, `dob`, `commute`, 
    `studentPhoto`, `password`, `dateCreated` 
) 
VALUES (
    '$regIdVal', '$studentName', '$classId', '$classSecId', '$fatherName', '$motherName', 
    '$priPhoneNo', '$secPhoneNo', '$address', '$zone', '$secLang', '$dob', '$commute', 
    '$studentPhoto', '$defaultPassword', '$dateCreated'
);
";
      // echo $sql; die();
      
      $query = mysqli_query($conn, $sql);

      if ($query) {
          $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while inserting!</div>";
      }
  }
}
//---------------------------------------SAVE-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id'], $_GET['action']) && $_GET['action'] == "edit") {
  $Id = mysqli_real_escape_string($conn, $_GET['Id']);

  // Fetch existing student data
  $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Id = '$Id'");
  $row = mysqli_fetch_assoc($query);

  if (!$row) {
      echo "<div class='alert alert-danger'>Student not found.</div>";
      exit;
  }

  // Update logic
  if (isset($_POST['update'])) {
      // Sanitize input
      // $regId = mysqli_real_escape_string($conn, $_POST['regId']);
      $studentName = mysqli_real_escape_string($conn, $_POST['studentName']);
      $classId = mysqli_real_escape_string($conn, $_POST['classId']);
      $classSecId = mysqli_real_escape_string($conn, $_POST['classSecId']);
      $fatherName = mysqli_real_escape_string($conn, $_POST['fatherName']);
      $motherName = mysqli_real_escape_string($conn, $_POST['motherName']);
      $priPhoneNo = mysqli_real_escape_string($conn, $_POST['priPhoneNo']);
      $secPhoneNo = mysqli_real_escape_string($conn, $_POST['secPhoneNo']);
      $address = mysqli_real_escape_string($conn, $_POST['address']);
      $zone = mysqli_real_escape_string($conn, $_POST['zone']);
      $secLang = mysqli_real_escape_string($conn, $_POST['secLang']);
      $dob = mysqli_real_escape_string($conn, $_POST['dob']);
      $commute = mysqli_real_escape_string($conn, $_POST['commute']);
      $dateCreated = date("Y-m-d");

      // Handle photo upload
      $studentPhoto = $row['studentPhoto']; // Default: existing photo

      if (!empty($_FILES['studentPhoto']['name'])) {
          $targetDir = "uploads/";
          $fileName = time() . '_' . basename($_FILES["studentPhoto"]["name"]);
          $targetFilePath = $targetDir . $fileName;
          $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

          $allowTypes = array('jpg', 'jpeg', 'png', 'gif');

          if (in_array($fileType, $allowTypes)) {
              if (move_uploaded_file($_FILES["studentPhoto"]["tmp_name"], $targetFilePath)) {
                  // Delete old photo if new uploaded
                  if (!empty($row['studentPhoto']) && file_exists($row['studentPhoto'])) {
                      unlink($row['studentPhoto']);
                  }
                  $studentPhoto = $targetFilePath;
              }
          }
      }

      // Perform update
      $updateQuery = "UPDATE tblstudents SET 
          -- regId = '$regId',
          studentName = '$studentName',
          classId = '$classId',
          classSecId = '$classSecId',
          fatherName = '$fatherName',
          motherName = '$motherName',
          priPhoneNo = '$priPhoneNo',
          secPhoneNo = '$secPhoneNo',
          address = '$address',
          zone = '$zone',
          secLang = '$secLang',
          dob = '$dob',
          commute = '$commute',
          studentPhoto = '$studentPhoto',
          dateCreated = '$dateCreated'
      WHERE Id = '$Id'";

      if (mysqli_query($conn, $updateQuery)) {
          echo "<script type='text/javascript'>
              alert('Student updated successfully!');
              window.location = 'createStudents.php';
          </script>";
          exit;
      } else {
          echo "<div class='alert alert-danger'>Error updating student: " . mysqli_error($conn) . "</div>";
      }
  }
}



//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];

  // Fetch the student to delete associated photo
  $fetchQuery = mysqli_query($conn, "SELECT studentPhoto FROM tblstudents WHERE Id = '$Id'");
  $student = mysqli_fetch_array($fetchQuery);
  $photoPath = $student['studentPhoto'];

  // Delete student record
  $query = mysqli_query($conn, "DELETE FROM tblstudents WHERE Id = '$Id'");

  if ($query) {
      // Delete photo file if it exists
      if (!empty($photoPath) && file_exists($photoPath)) {
          unlink($photoPath);
      }

      echo "<script type='text/javascript'>
          window.location = ('createStudents.php');
      </script>";
  } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while deleting!</div>";
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
            <h1 class="h3 mb-0 text-gray-800">Create Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Students</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Students</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">

                <form id="studentForm" method="post" enctype="multipart/form-data">
  <!-- <div class="form-group row mb-3">
    <div class="col-xl-6">
      <label class="form-control-label">Student ID</label>
      <input type="text" class="form-control" name="Id" value="<?php echo htmlspecialchars($row['Id'] ?? ''); ?>" >
    </div>
    <div class="col-xl-6">
      <label class="form-control-label">Registration ID <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="regId" value="<?php echo htmlspecialchars($row['regId'] ?? ''); ?>" required>
    </div>
  </div> -->

  <div class="form-group row mb-3">
    <div class="col-xl-6">
      <label class="form-control-label">Student Name <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="studentName" value="<?php echo htmlspecialchars($row['studentName'] ?? ''); ?>" required>
    </div>
    <div class="col-xl-6">
      <label class="form-control-label">Class <span class="text-danger">*</span></label>
      <select name="classId" required class="form-control">
        <option value="">--Select Class--</option>
        <?php
        $qry = "SELECT * FROM tblclass ORDER BY className ASC";
        $result = $conn->query($qry);
        while ($cls = $result->fetch_assoc()) {
          $selected = ($row['classId'] ?? '') == $cls['Id'] ? 'selected' : '';
          echo "<option value='{$cls['Id']}' $selected>{$cls['className']}</option>";
        }
        ?>
      </select>
    </div>
  </div>

  <div class="form-group row mb-3">
    <div class="col-xl-6">
      <label class="form-control-label">Class Section <span class="text-danger">*</span></label>
      <select name="classSecId" required class="form-control">
        <option value="">--Select Section--</option>
        <?php
        $qrySec = "SELECT * FROM tblclassarms ORDER BY classArmName ASC";
        $resSec = $conn->query($qrySec);
        while ($sec = $resSec->fetch_assoc()) {
          $selected = ($row['classSecId'] ?? '') == $sec['Id'] ? 'selected' : '';
          echo "<option value='{$sec['Id']}' $selected>{$sec['classArmName']}</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-xl-6">
      <label class="form-control-label">Father Name <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="fatherName" value="<?php echo htmlspecialchars($row['fatherName'] ?? ''); ?>" required>
    </div>
  </div>

  <div class="form-group row mb-3">
    <div class="col-xl-6">
      <label class="form-control-label">Mother Name <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="motherName" value="<?php echo htmlspecialchars($row['motherName'] ?? ''); ?>" required>
    </div>
    <div class="col-xl-6">
      <label class="form-control-label">Primary Phone No. <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="priPhoneNo" value="<?php echo htmlspecialchars($row['priPhoneNo'] ?? ''); ?>" required>
    </div>
  </div>

  <div class="form-group row mb-3">
    <div class="col-xl-6">
      <label class="form-control-label">Secondary Phone No.</label>
      <input type="text" class="form-control" name="secPhoneNo" value="<?php echo htmlspecialchars($row['secPhoneNo'] ?? ''); ?>">
    </div>
    <div class="col-xl-6">
      <label class="form-control-label">Address <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($row['address'] ?? ''); ?>" required>
    </div>
  </div>

  <div class="form-group row mb-3">
    <div class="col-xl-6">
      <label class="form-control-label">Zone <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="zone" value="<?php echo htmlspecialchars($row['zone'] ?? ''); ?>" required>
    </div>
    <div class="col-xl-6">
  <label class="form-control-label">Second Language</label>
  <select class="form-control" name="secLang">
  <option value="" >-- Select Language --</option>
    <option value="Hindi" <?php echo (isset($row['secLang']) && $row['secLang'] == 'Hindi') ? 'selected' : ''; ?>>Hindi</option>
    <option value="Bengali" <?php echo (isset($row['secLang']) && $row['secLang'] == 'Bengali') ? 'selected' : ''; ?>>Bengali</option>
  </select>
</div>
  </div>
  <div class="form-group row mb-3">
  <div class="col-xl-6">
  <label class="form-control-label">Date of Birth</label>
  <input type="date" class="form-control" name="dob" value="<?php echo htmlspecialchars($row['dob'] ?? ''); ?>">
</div>
<div class="col-xl-6">
  <label class="form-control-label">Mode of Commute</label>
  <select class="form-control" name="commute">
    <option value="">-- Please select your mode of commute --</option>
    <option value="Walking" <?php echo (isset($row['commute']) && $row['commute'] == 'Walking') ? 'selected' : ''; ?>>On foot</option>
    <option value="Car" <?php echo (isset($row['commute']) && $row['commute'] == 'Car') ? 'selected' : ''; ?>>By car</option>
    <option value="Public Transport" <?php echo (isset($row['commute']) && $row['commute'] == 'Public Transport') ? 'selected' : ''; ?>>By public transport</option>
    <option value="Other" <?php echo (isset($row['commute']) && $row['commute'] == 'Other') ? 'selected' : ''; ?>>Other</option>
  </select>
</div>

    </div>


  <div class="form-group row mb-3">
    <div class="col-xl-6">
      <label class="form-control-label">Student Photo</label>
      <input type="file" class="form-control-file" name="studentPhoto">
      <?php if (!empty($row['studentPhoto'])): ?>
        <small>Current: <a href="uploads/<?php echo htmlspecialchars($row['studentPhoto']); ?>" target="_blank">View</a></small>
      <?php endif; ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-xl-12">
      <?php
      if (!empty($row['Id'])) {
        echo '<button type="submit" name="update" class="btn btn-warning">Update</button>';
      } else {
        echo '<button type="submit" name="save" class="btn btn-primary">Save</button>';
      }
      ?>
    </div>
  </div>
</form>







                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Student</h6>
                </div>
                <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
  <thead class="thead-light">
    <tr>
      <th>#</th>
      <th>Reg ID</th>
      <th>Student Name</th>
      <th>Class</th>
      <th>Class Section</th>
      <th>Father Name</th>
      <th>Mother Name</th>
      <th>Primary Phone</th>
      <th>Secondary Phone</th>
      <th>Address</th>
      <th>Zone</th>
      <th>Second Language</th>
      <th>Date of Birth</th>
      <th>Mode of Commute</th>
      <th>Date Created</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>

  <tbody>
    <?php
    $query = "SELECT s.Id, s.regId, s.studentName, s.fatherName, s.motherName, s.priPhoneNo, s.secPhoneNo,
                     s.address, s.zone, s.secLang, s.dob,s.commute, s.dateCreated,
                     c.className, a.classArmName AS classSecName
              FROM tblstudents s
              INNER JOIN tblclass c ON c.Id = s.classId
              INNER JOIN tblclassarms a ON a.Id = s.classSecId";

    $rs = $conn->query($query);
    $sn = 0;

    if ($rs->num_rows > 0) {
      while ($row = $rs->fetch_assoc()) {
        $sn++;
        echo "
          <tr>
            <td>{$sn}</td>
            <td>{$row['regId']}</td>
            <td>{$row['studentName']}</td>
            <td>{$row['className']}</td>
            <td>{$row['classSecName']}</td>
            <td>{$row['fatherName']}</td>
            <td>{$row['motherName']}</td>
            <td>{$row['priPhoneNo']}</td>
            <td>{$row['secPhoneNo']}</td>
            <td>{$row['address']}</td>
            <td>{$row['zone']}</td>
            <td>{$row['secLang']}</td>
             <td>{$row['dob']}</td>
              <td>{$row['commute']}</td>
            <td>{$row['dateCreated']}</td>
            <td><a href='?action=edit&Id={$row['Id']}'><i class='fas fa-fw fa-edit'></i></a></td>
            <td><a href='?action=delete&Id={$row['Id']}' onclick=\"return confirm('Are you sure you want to delete this student?');\"><i class='fas fa-fw fa-trash'></i></a></td>
          </tr>";
      }
    } else {
      echo "
        <tr>
          <td colspan='15'>
            <div class='alert alert-danger' role='alert'>
              No Record Found!
            </div>
          </td>
        </tr>";
    }
    ?>
  </tbody>
</table>





                </div>
              </div>
            </div>
            </div>
          </div>
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

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



  </script>
<!-- <script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("studentForm");

    if (!form) {
      console.warn("Form not found!");
      return;
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault(); // Prevent actual submission

      const formData = new FormData(form);
      const data = {};

      formData.forEach((value, key) => {
        if (value instanceof File) {
          data[key] = value.name || "(no file selected)";
        } else {
          data[key] = value;
        }
      });

      console.log("Form submitted. Data:", data);

      
    });
  });
</script> -->


</body>

</html>