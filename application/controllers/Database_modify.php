 <?php defined('BASEPATH') OR exit('No direct script access allowed');

class Database_modify{

	// Function to create the tables and fill them with the default data
    function deleteAndCreateDatabase()
    {

    	$database="inventory_test1";

        // Connect to the database
        $mysqli = mysqli_connect("localhost", "root", "", $database);

        // Check for errors
        if (mysqli_connect_errno()){
            echo "mysqli not connect";
        }
        

		/* query all tables */
		$sql = "SHOW TABLES";
		if($result = mysqli_query($mysqli,$sql)){
		  /* add table name to array */
		  while($row = mysqli_fetch_row($result)){
		    $found_tables[]=$row[0];
		  }
		}
		else{
		  die("Error, could not list tables.");
		}

		/* loop through and drop each table */
		foreach($found_tables as $table_name){
		  $sql = "DROP TABLE $database.$table_name";
		  if($result = mysqli_query($mysqli,$sql)){
		  }
		  else{
		  }
		}

		// Open the default SQL file
        $query = file_get_contents('install/sql/install.sql');


        $multi_query = $mysqli->multi_query($query);


        $mysqli->close();

        if ($multi_query){
       
        } else {
        
        }
    }
}
?>


 