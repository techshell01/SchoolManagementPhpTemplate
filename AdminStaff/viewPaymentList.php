
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['payment_id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $payment_id= $_GET['payment_id'];

        $query=mysqli_query($conn,"select * from payments where payment_id ='$payment_id'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------
        if (isset($_POST['update'])) {
          $reg_num = $_POST['reg_num'];
          $first_name = $_POST['first_name'];
          $amount = $_POST['amount'];
          $status = $_POST['status']; 
      
          $query = mysqli_query($conn, "UPDATE payments 
              SET reg_num='$reg_num', first_name='$first_name', amount='$amount', status='$status'
              WHERE payment_id='$payment_id'");
      
      if ($query) {
        $statusMsg = "<div class='alert alert-success'>Payment record updated successfully!</div>";
        echo $statusMsg;
    
        // JS redirect after 2 seconds
        echo "<script>
            setTimeout(function() {
                window.location.href = 'viewPaymentList.php';
            }, 2000);
        </script>";
        exit;
    }
     else {
              $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
          }
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
            <h1 class="h3 mb-0 text-gray-800">Approve/Rejects Payments</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Approve/Rejects Payments</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['payment_id'])): ?>
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Approve/Rejects Payments</h6>
                  
                  <?php if (!empty($statusMsg)) echo $statusMsg; ?>

                </div>
                
                <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                   <div class="col-xl-6">
                        <label class="form-control-label">Reg number<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control"readonly name="reg_num" value="<?php echo $row['reg_num'];?>" id="exampleInputFirstName" >
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control"readonly name="first_name" value="<?php echo $row['first_name'];?>" id="exampleInputFirstName" >
                        </div>
                        
                    </div>
                     <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Amount<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" readonly name="amount" value="<?php echo $row['amount'];?>" id="exampleInputFirstName" >
                        </div>
                        <!-- <div class="col-xl-6">
                        <label class="form-control-label">Status<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" required name="status" value="<?php echo $row['status'];?>" id="exampleInputFirstName" >
                        </div> -->
                        <div class="col-xl-6">
                        <label class="form-control-label">Status <span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="status" required>
                          <option value="">-- Select Status --</option>
                          <option value="Approve" <?php if($row['status'] == 'Approve') echo 'selected'; ?>>Approve</option>
                          <option value="Reject" <?php if($row['status'] == 'Reject') echo 'selected'; ?>>Reject</option>
                        </select>
                      </div>

                    </div>
               
                      <?php
                    if (isset($payment_id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <?php
                    }         
                    ?>
                  </form>
                </div>

              </div>
              <?php endif; ?>

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
                        <th>Name</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Photo</th>
                        <th>Status</th>
                         <th>Edit</th>
                      </tr>
                    </thead>
                
                    <tbody>

                  <?php
                      $query = "SELECT payment_id, first_name, payment_type, amount, created_at,photo, status FROM payments ORDER BY created_at DESC";

                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['first_name']."</td>
                                <td>".$rows['payment_type']."</td>
                                <td>".$rows['amount']."</td>
                                <td>".$rows['created_at']."</td>
                                <td>";
                              if (!empty($rows['photo'])) {
                                  echo "<a href='../Student/img/upload/" . $rows['photo'] . "' download title='Download Image'>
                                          <i class='fas fa-download' style='font-size:18px; color:#007bff;'></i>
                                        </a>";
                              } else {
                                  echo "No Photo";
                              }

                              echo "</td>


                                <td>".$rows['status']."</td>
                                <td><a href='?action=edit&payment_id=".$rows['payment_id']."&action=edit'><i class='fas fa-fw fa-edit'></i></a></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
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
</body>

</html>