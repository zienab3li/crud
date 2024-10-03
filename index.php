<?php
/**
 * database connection
 */
$host = "localhost";
$username = "root";
$password = '';
$db='company';
$connect = mysqli_connect($host, $username, $password, $db);
// if($connect){
//     echo "connection established";
// }
//delete data
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $deleteQuery="DELETE FROM `employees` WHERE id=$id";
    $delete=mysqli_query($connect,$deleteQuery);
    header("Location: index.php");
}
//variable initialize..........
    $name = '';
    $phone ='';
    $email = '';
    $gender = '';
    $department = '';
    $empid='';
    $mode='create';
//EDIT============>
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    
    $selectBYID="SELECT * FROM `employees` WHERE id = $id";
    $selectBYIDresult=mysqli_query($connect,$selectBYID);
    $row=mysqli_fetch_assoc($selectBYIDresult);
    $name = $row['name'];
    $phone = $row['phone'];
    $email = $row['email'];
    $gender = $row['gender'];
    $department = $row['department'];
    $empid=$id;
    $mode='update';
    // echo "$name ,$phone,$email,$gender,$department";

}
//update data
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $updateQuery="UPDATE `employees` SET `name`= '$name',phone='$phone', email='$email',gender='$gender', department='$department'  WHERE id=$empid";
    $update = mysqli_query($connect,$updateQuery);
    header("Location: index.php");
}

//CREATE DATA
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    // echo "$name ,$phone,$email,$gender,$department";
    $insertQuery = "INSERT INTO `employees` VALUES (NULL,'$name','$email','$phone','$gender','$department')";
    $insert= mysqli_query($connect,$insertQuery);
}

//READ DATA , search & orderBy
$search='';
$message='';
$select="SELECT * FROM `employees`";
if(isset($_GET['search'])){
    $value= $_GET['search'];
    $search=$value;
    $select="SELECT * FROM `employees` where `name` like '%$value%' or email like '%$value%' or department like '$value'";
}

 
// foreach($result as $emp){
//     echo $emp['name']." ".$emp['email']." ".$emp['phone']." ".$emp['gender']." ".$emp['department'];
//      echo '<br>';
// }
if(isset($_GET['asc'])){
    if(!isset($_GET['orderBy'])){
        $message='Please select a column!!!';
    }
    else{
    $order=$_GET['orderBy'];
    $select="SELECT * FROM `employees` ORDER BY $order ASC";
    }
}
if(isset($_GET['desc'])){
    if(!isset($_GET['orderBy'])){
        $message='Please select a column!!!';
    }
    else{
    $order=$_GET['orderBy'];
    $select="SELECT * FROM `employees` ORDER BY $order DESC";
    }
}
$result = mysqli_query($connect, $select);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <style>
        body{
        background-color: #333;
        color: white;

        }
     </style>
</head>
<body>

   <div class="container py-2">
    <div class="card bg-dark text-light">
        <div class="card-body">
            <form method='POST'>
                <div class="mb-3">
                    
                    <label for="name" class="form-label">Name</label>
                    <!-- input must have name عشان يسمع فال database -->
                    <input type="text" value="<?=$name?>" name='name' class="form-control">
                </div>
                <div class="mb-3">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" value="<?=$email?>" name='email' class="form-control">
                </div>
                <div class="mb-3">
                    <label for="Phone" class="form-label">Phone</label>
                    <input type="text" value="<?=$phone?>" name='phone' class="form-control">
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">gender</label>
                    <select  id="gender" name='gender' class="form-select">
                        <?php if($gender=='male'):?>
                        <option disabled >Choose</option>
                        <option  value="male" selected>Male</option>
                        <option value="female">Female</option>
                        <?php elseif($gender=='female'):?> 
                        <option disabled >Choose</option>
                        <option  value="male">Male</option>
                        <option value="female" selected>Female</option>
                        <?php else:?>       
                        <option disabled selected>Choose</option>
                        <option  value="male">Male</option>
                        <option value="female">Female</option>
                        <?php endif;?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="department" class="form-label">department</label>
                    <input type="text" value="<?=$department?>" name='department' class="form-control">
                </div>
               <div class="col-12 text-center">
                <?php if($mode=='update'):?>
                 <button class="btn btn-warning" name="update">UPDATE</button>
                 <a href="index.php" class="btn btn-secondary">CANCEL</a> 
                 <?php else:?>  
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <?php endif;?>
               </div>

            </form>
        </div>
    </div>
   </div>

   <div class="container py-2">
    <div class="card bg-dark text-light">
        <div class="card-body">
            <h2 class="text-center">FILTERS</h2>
            <form >
                <div class="mb-3">
                <label for="search" class="form-label">SEARCH</label>
                 <div class="input-group">
                 <input type="text" class="form-control" value="<?= $search;?>" name="search" id='search'>
                 <button class="btn btn-primary">SEARCH</button>
                 </div>
                </div>
            </form>
            <form >
                <h5 class="text-danger">
                    <?=$message?>
                </h5>
                <div class="row align-items-center">
                    <div class="col-md-8 mb-3">
                        <label for="orderBy">Order By</label>
                        <select id="orderBy" name="orderBy" class="form-select">
                            <option  disabled selected>choose..</option>
                            <option value="id">Id</option>
                            <option value="name">Name</option>
                            <option value="email">Email</option>
                            <option value="phone">Phone</option>
                            <option value="gender">Gender</option>
                            <option value="department">Department</option>
                        </select>
                            
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <button class=" btn btn-info" name="asc"> ASC</button>
                            <button class=" btn btn-info" name="desc">DESC</button>
                        </div>
                   </div>
                </div>
                <a  href= "./index.php" class=" btn btn-secondary" >CANCEL</a>
            </form>
        </div>
    </div>
   </div>


    <div class="container py-2">
        <div class="card bg-dark">
        <div class="card-body">
        <table class="table table-dark">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>name</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>gender</th>
                    <th>department</th>
                    </tr>
                </thead>
                <tbody>
                <!-- data loop  data read-->
                 <?php foreach($result as $index=>$emp): ?>
                <tr>
                    <td><?= $index+1;?></td>
                    <td><?= $emp['name'];?></td>
                    <td><?= $emp['email'];?></td>
                    <td><?= $emp['phone'];?></td>
                    <td><?= $emp['gender'];?></td>
                    <td><?= $emp['department'];?></td>
                    <td>
                        <!-- <form >
                            <input type="text" name= "delete" hidden value="">
                            <button class= "btn btn-danger">Delete</button>
                        </form> -->
                        <a href="?edit=<?= $emp['id'];?>" class= "btn btn-warning">EDIT</a>
                        <a href="?delete=<?= $emp['id'];?>" class= "btn btn-danger">DELETE</a>
                    </td>
                    
                </tr>
                <?php endforeach; ?>
                
                <!-- end loop -->
                </tbody>
                </table>
            </div>
        </div>
    
    </div>
</body>
</html>