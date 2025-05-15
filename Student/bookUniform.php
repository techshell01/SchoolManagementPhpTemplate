
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
            <h1 class="h3 mb-0 text-gray-800">Book & Uniform</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Book & Uniform</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Pay Book & Uniform Fees</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                  <div class="form-group row mb-3">
                  <div class="col-xl-8">
                     <div class="form-group row mb-3"> 
                        <div class="col-xl-4">
                           <?php
// Book List
$bookData = [];
$result = $conn->query("SELECT * FROM book_list");
while ($row = $result->fetch_assoc()) {
    $code = $row['class_code'];
    $bookData[$code][] = [
        'book_name' => $row['book_name'],
        'amount' => $row['amount']
    ];
}

// Notebook List
$notebookData = [];
$result2 = $conn->query("SELECT * FROM notebook_list");

if (!$result2) {
    die("Notebook Query Failed: " . $conn->error);
}

while ($row = $result2->fetch_assoc()) {
    $code = $row['class_code'];
    $notebookData[$code][] = [
        'subject' => $row['subject'],
        'quantity' => $row['quantity'],
        'details' => $row['details'],
        'amount' => $row['amount']
    ];
}
?>

<!-- HTML -->
<!-- Selectors Section -->
<div style="display: flex; gap: 30px; margin: 20px;">
    <!-- Select Class -->
    <div>
        <label for="class_selector" style="font-weight: 500; font-size: 16px;">Select Class</label><br>
        <select id="class_selector" onchange="populateBookList()"
            style="width: 220px; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;">
            <option value="">-- Select Class --</option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="Class <?= $i ?>">Class <?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <!-- Select Book List -->
    <div>
        <label for="book_list_selector" style="font-weight: 500; font-size: 16px;">Select Book List</label><br>
        <select id="book_list_selector" onchange="renderData()"
            style="width: 220px; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;">
            <option value="">-- Select Book List --</option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="class<?= $i ?>_A">Class <?= $i ?> - Book List A</option>
                <option value="class<?= $i ?>_B">Class <?= $i ?> - Book List B</option>
            <?php endfor; ?>
        </select>
    </div>
</div>

<!-- Tables Section -->
<div style="display: flex; gap: 40px; margin: 20px;">
    <!-- Book List Table -->
    <div>
        <h4 style="margin-bottom: 10px;">Book List</h4>
        <table id="book_table" class="table table-bordered" style="border-collapse: collapse; width: 300px; font-size: 15px;">
            <thead>
                <tr>
                    <th style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ccc;">Book Name</th>
                    <th style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ccc;">Amount</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th style="padding: 8px; border: 1px solid #ccc;">Total</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">₹<span id="book_total">0</span></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Notebook List Table -->
    <div>
        <h4 style="margin-bottom: 10px;">Notebook List</h4>
        <table id="notebook_table" class="table table-bordered" style="border-collapse: collapse; width: 380px; font-size: 15px;">
            <thead>
                <tr>
                    <th style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ccc;">Subject</th>
                    <th style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ccc;">Qty</th>
                    <th style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ccc;">Details</th>
                    <th style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ccc;">Amount</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="padding: 8px; border: 1px solid #ccc;">Total</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">₹<span id="notebook_total">0</span></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Grand Total -->
<!-- <h4 style="margin-left: 20px; margin-top: 10px;">Grand Total: ₹<span id="total_amount">0</span></h4> -->

<script>
const bookData = <?php echo json_encode($bookData); ?>;
const notebookData = <?php echo json_encode($notebookData); ?>;


function populateBookList() {
    const classVal = document.getElementById("class_selector").value;
    if (!classVal) return;

    const classNumber = classVal.split(' ')[1];
    const selector = document.getElementById("book_list_selector");
    selector.value = "";
    selector.innerHTML = `
        <option value="">-- Select Book List --</option>
        <option value="class${classNumber}_A">Class ${classNumber} - Book List A</option>
        <option value="class${classNumber}_B">Class ${classNumber} - Book List B</option>
    `;
}

function renderData() {
    const selectedCode = document.getElementById("book_list_selector").value;

    // Books
    const books = bookData[selectedCode] || [];
    let bookRows = '';
    let bookTotal = 0;
    books.forEach(book => {
        bookRows += `<tr><td>${book.book_name}</td><td>₹${book.amount}</td></tr>`;
        bookTotal += parseFloat(book.amount);
    });
    document.querySelector("#book_table tbody").innerHTML = bookRows;
    document.getElementById("book_total").textContent = bookTotal;

    // Notebooks
    const notes = notebookData[selectedCode] || [];
    let noteRows = '';
    let noteTotal = 0;
    notes.forEach(note => {
        noteRows += `<tr>
            <td>${note.subject}</td>
            <td>${note.quantity}</td>
            <td>${note.details}</td>
            <td>₹${note.amount}</td>
        </tr>`;
        noteTotal += parseFloat(note.amount);
    });
    document.querySelector("#notebook_table tbody").innerHTML = noteRows;
    document.getElementById("notebook_total").textContent = noteTotal;

    // Grand Total
    document.getElementById("total_amount").textContent = bookTotal + noteTotal ;
}
</script>
                       
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
    
    <div class="col-md-6">
        <label style="margin-top:15px;">Price</label>
        <input type="text" class="form-control" id="tie_price" readonly placeholder="Auto-filled"
            style="width: 100%; padding: 10px; margin-right:128px;">
    </div>
</div>
<div class="col-md-4">
            <label style="margin-top:15px;">Other</label>
            <input type="number" class="form-control" id="other_amount" placeholder="Enter amount"
                oninput="calculateGrandTotal()" style="margin-top:5px;">
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
                  <div class="col-xl-4 text-center">
    <!-- QR Code Image -->
    <img src="img/schoolQR.JPG" name="qr" style="height: 452px; width: 260px;" />

    <!-- Bank Name below QR -->
    <div style="margin-top: 15px;">
        <label class="form-control-label">Bank Name</label>
        <input type="text" class="form-control mx-auto" style="max-width: 260px;" required name="bank_name" readonly value="Punjab National Bank">
    </div>
    
     <div style="margin-top: 15px;">
     <label class="form-control-label">Account Number</label>
       <input type="text" class="form-control mx-auto" style="max-width: 260px;" required name="ac number" readonly value="0564056000010" id="exampleInputFirstName" >
     </div>

     <div style="margin-top: 15px;">
     <label class="form-control-label">RTGS/NEFT IFSC Code</label>
       <input type="text" class="form-control mx-auto" style="max-width: 260px;" required name="ifc code" readonly value="PUNB0056420" id="exampleInputFirstName" >
     </div>
</div>
</div>


<div class="form-group row mb-3" style="margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: flex-start; gap: 40px; margin-bottom: 30px;">

        <!-- Left Side -->
        <div style="flex: 1;">

            <!-- Grand Total + Amount Paying side-by-side -->
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                
                <!-- Grand Total box -->
                  <div style="flex: 1; border: 1px solid #ccc; padding: 10px 15px; border-radius: 6px; background: #f9f9f9; width:30vh">
                <label style="font-size: 14px; font-weight: 600; color: #444;">Grand Total</label>
                <div style="font-size: 18px; font-weight: bold; color: #222;">₹<span id="total_amount">0</span></div>
            </div>

                <!-- Amount Paying input -->
                <div style="flex: 1;">
                    <label style="font-weight: bold;display: inline; font-size: 15px;">Amount Paying (₹)</label><br>
                    <input type="number" name="amountPaying" id="amountPaying"
                        placeholder="Enter amount"
                        style="padding: 10px 12px; border-radius: 6px; border: 1px solid #ccc; width: 30vh; font-size: 14px;" required>
                </div>
            </div>

            <!-- File Upload -->
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; font-size: 15px;">Upload Payment Receipt </label><br>
                <input type="file" name="paymentImage" onchange="displayImage(this)" id="paymentImage"
                    style="padding: 6px 10px; border-radius: 6px; border: 1px solid #666; width: 100%; font-size: 14px; background-color: #fff;" required>
            </div>

            <!-- Save Button -->
            <button type="submit" name="save"
                style="padding: 10px 25px; font-size: 16px; background-color: #4e73df; color: white; border: none; border-radius: 6px; cursor: pointer;">
                Save
            </button>
        </div>

        <!-- Right Side: Image Preview -->
        <div>
            <img src="img/logo/attnlg.jpg" onclick="triggerClick()" id="paymentDisplay" alt="Click to upload"
                style="width: 150px; height: 150px; border: 2px solid #ccc; border-radius: 10px; object-fit: cover; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        </div>
    </div>
</div>


                    
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