<?php

//Include config file
require_once 'config.php';

//Define variables and initialize with empty values

$taskname = $details = "";
$taskname_err = $details_err = "";

//Processing form data when form is submitted

    if(isset($_POST["id"]) && !empty($_POST["id"])){

    //Get hidden input value

    $id = $_POST["id"];
  
    //Validate Task

    $input_name = trim($_POST["taskname"]);

        if(empty($input_name)){

            $taskname_err = "Please enter the Task";

        } elseif(!filter_var(trim($_POST["taskname"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){

            $taskname_err = 'Please enter a valid Task.';

        } else{

	echo "OK 1";

            $taskname = $input_name;

        }

        

    //Validate Task details

    $input_details = trim($_POST["details"]);

        if(empty($input_details)){

            $details_err = 'Please enter the Details';     

        } else{

	echo "OK 2";

            $details = $input_details;

        }
        

    //Check input errors before inserting in database

        if(empty($taskname_err) && empty($details_err) ){

	echo "OK 3";

    //Prepare an update statement

    $sql = "UPDATE LIVETABLE SET TASKNAME=?, DETAILS=? WHERE ID=?";

    //echo $sql;

	//$stmt = mysqli_prepare($link, $sql);
	//$stmt = mysqli_prepare($link, "UPDATE LIVETABLE SET TASKNAME=?, DETAILS=? WHERE ID=?");

	//mysqli_stmt_bind_param($stmt, "ssi", $param_taskname, $param_details, $param_id);

	//echo "<br>";
	//echo mysqli_error($link);

	//echo "<br>";
	//echo $taskname;
	//echo "<br>";
	//echo $details;
	//echo "<br>";
	//echo $id;
	//echo "<br>";

//$param_taskname = $taskname;
//$param_details = $details;
//$param_id = $id;

	//$param_taskname = $taskname;

        //$param_details = $details;

        //$param_id = $id;

	//mysqli_stmt_execute($stmt);

	//mysqli_stmt_close($stmt);

        //mysqli_close($link);



    if($stmt = mysqli_prepare($link, $sql)){

	//echo "OK 4";
    //Bind variables to the prepared statement as parameters

    mysqli_stmt_bind_param($stmt, 'ssi', $param_taskname, $param_details, $param_id);

    //Set parameters

    $param_taskname = $taskname;

    $param_details = $details;

    $param_id = $id;

    //Attempt to execute the prepared statement

    if(mysqli_stmt_execute($stmt)){

		echo "OK 4";

        // Records updated successfully. Redirect to landing page

        header("location: index.php");

        exit();

                                  } else{
                    echo "Something went wrong. Please try again later.";
                                        }

                                            }

    //Close statement
    mysqli_stmt_close($stmt);

                                                            }

    //Close connection
    mysqli_close($link);

                                                        } else{

    //Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

    //Get URL parameter
    $id =  trim($_GET["id"]);

    //Prepare a select statement
    $sql = "SELECT * FROM LIVETABLE WHERE ID = ?";

       if($stmt = mysqli_prepare($link, $sql)){

       //Bind variables to the prepared statement as parameters
       mysqli_stmt_bind_param($stmt, "i", $param_id);

       // Set parameters
       $param_id = $id;

                

       // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){

            /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            //Retrieve individual field value

            $taskname = $row["TASKNAME"];

            $details = $row["DETAILS"];

                                                } else{

            //URL doesn't contain valid id. Redirect to error page

            header("location: error.php");

            exit();

                                                    }

                                            } else{

            echo "Oops! Something went wrong. Please try again later.";

                                                  }

                                                }

            // Close statement
            mysqli_stmt_close($stmt);

            // Close connection
            mysqli_close($link);
                                                            }  else{

            // URL doesn't contain id parameter. Redirect to error page

            header("location: error.php");

            exit();

                                                                    }

                                                                    }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Update Record</title>
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
<h2>Update Record</h2>
</div>
<p>Please edit the input values and submit to update the record.</p>

<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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

<input type="hidden" name="id" value="<?php echo $id; ?>"/>

<input type="submit" class="btn btn-primary" value="Submit">
<a href="index.php" class="btn btn-default">Cancel</a>

</form>
</div>
</div>        
</div>
</div>
</body>
</html>
