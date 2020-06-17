<?php

//Include config file
require_once 'config.php';

//Define variables and initialize with empty values
$taskname = $details = "";
$taskname_err = $details_err = "";
     
//Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //Validate name
    $input_name = trim($_POST["taskname"]);
    
    if(empty($input_name)){

    $taskname_err = "Please enter a name.";

    } elseif(!filter_var(trim($_POST["taskname"]))){

        $taskname_err = 'Please enter a valid task name.';

    } else{
            $taskname = $input_name;
          }

    //Validate address
        $input_address = trim($_POST["details"]);

        if(empty($input_address)){

            $details_err = 'Please enter the details.';     

                                 } else{
            $details = $input_address;
                                       }
        
    //Check input errors before inserting in database
    if(empty($taskname_err) && empty($details_err)){

        //Prepare an insert statement

        $sql = "INSERT INTO LIVETABLE (TASKNAME, DETAILS) VALUES (?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){

        //Bind variables to the prepared statement as parameters

        //CADA "s" representa uma entrada de variavel

                mysqli_stmt_bind_param($stmt, "ss", $param_taskname, $param_details);

        //Set parameters

        $param_taskname = $taskname;
        $param_details = $details;
                

        // Attempt to execute the prepared statement

        if(mysqli_stmt_execute($stmt)){

        //Records created successfully. Redirect to landing page

        header("location: index.php");

        exit();

                                      } else{

            echo "Something went wrong. Please try again later.";
			echo "<br>";
			echo $param_taskname;
			echo "<br>";
			echo $taskname;
                	echo "<br>";
			echo $param_details;
			echo "<br>";
			echo $details;
			echo "<br>";
                }

            }

            // Close statement

            mysqli_stmt_close($stmt);

        }        

        // Close connection

        mysqli_close($link);

    }
?>

     
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Record</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

<style type="text/css">
.wrapper{
    width: 500px;
    margin: 0 auto;
         }
</style>
</head>
<body>
<div class="wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="page-header">
<h2>Create Record</h2>
</div>

<p>Please fill this form and submit to add a new task record to the database.</p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

<div class="form-group <?php echo (!empty($taskname_err)) ? 'has-error' : ''; ?>">

<label>Task Name</label>

<input type="text" name="taskname" class="form-control" value="<?php echo $taskname; ?>">

<span class="help-block"><?php echo $taskname_err;?></span>

</div>

<div class="form-group <?php echo (!empty($details_err)) ? 'has-error' : ''; ?>">

<label>Details</label>

<textarea name="details" class="form-control"><?php echo $details; ?></textarea>

<span class="help-block"><?php echo $details_err;?></span>

</div>

<input type="submit" class="btn btn-primary" value="Submit">

<a href="index.php" class="btn btn-default">Cancel</a>

</form>
</div>
</div>        
</div>
</div>
</body>
</html>
