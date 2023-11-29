<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->
<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "ora_carolm03";			// change "cwl" to your own CWL
$config["dbpassword"] = "a17849571";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP
?>

<html>
<head>
    <title>Job Portal</title>
</head>
<h2>Display Selected Attributes from Selected Table</h2>
<?php
if (isset($_GET['table'])) {
    global $db_conn;
    connectToDB();
    $selectedTable = $_GET['table'];

    $result = executePlainSQL("SELECT column_name FROM all_tab_columns WHERE table_name = '$selectedTable'");
    oci_commit($db_conn);

    $allAttributes = array();
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        array_push($allAttributes, $row['COLUMN_NAME']);
    }

    if (count($allAttributes) == 0){
        echo "<p>Selected table does not exist or has no attributes.</p>";
    } else{
        echo "<form method='get' action='job_portal_view.php'>";
        echo "<input type='hidden' name='selectedTable' value='$selectedTable'>";
        echo "<label for='attributes[]'>Choose attribute(s) for $selectedTable:</label><br>";
        foreach ($allAttributes as $attribute) {
            echo "<input type='checkbox' id='$attribute' name='attributes[]' value='$attribute'>";
            echo "<label for='$attribute'>$attribute</label><br>";
        }
        echo "<br><input type='submit' value='Submit' name='searchSubmit'>";
        echo "</form>";

        echo "<form method='get' action='job_portal_view.php'>";
        echo "<input type='submit' value='Go back to select table'>";
        echo "</form>";

    }

} else { // if no table selected, display drop down
    ?>
    <form method="get" action="job_portal_view.php">
        <label for="table">Choose a table:</label>
        <select id="table" name="table">
            <?php
            $tables = getAllTables();
            foreach ($tables as $table) {
                echo "<option value='$table'>$table</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Submit">
    </form>
    <?php
}
?>
<a href="job_portal.php"><button>Go Back to Main Page</button></a>
<br><br>
<?php
function getAllTables() {
    global $db_conn;

    if (connectToDB()){
        $result = executePlainSQL("SELECT table_name FROM user_tables");
        oci_commit($db_conn);

        $tableNames = array();
        while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
            array_push($tableNames, $row['TABLE_NAME']);
        }

        return $tableNames;

    } else{
        echo "Cannot connect to the database";
    }


}
?>


<?php
// The following code will be parsed as PHP

function debugAlertMessage($message)
{
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr)
{ //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    $statement = oci_parse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = oci_execute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For oci_execute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }


    return $statement;
}

function executeBoundSQL($cmdstr, $list)
{
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
    In this case you don't need to create the statement several times. Bound variables cause a statement to only be
    parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
    See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = oci_parse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            oci_bind_by_name($statement, $bind, $val);
            unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = oci_execute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}


function connectToDB()
{
    global $db_conn;
    global $config;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    // $db_conn = oci_connect("ora_cwl", "a12345678", "dbhost.students.cs.ubc.ca:1522/stu");
    $db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For oci_connect errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB()
{
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    oci_close($db_conn);
}

function handleSelectRequest() {
    global $db_conn;

    if (!isset($_GET["attributes"]) || count($_GET["attributes"]) === 0) {
        echo ("<p style='color: red;'>No attribute selected.</p>");
        return;
    }
    $attributes = implode(", ", $_GET['attributes']);

    $result = executePlainSQL("SELECT ". $attributes . " FROM ". $_GET["selectedTable"]);

    // generate table to display result
    echo "<table border='1'><tr>";

    foreach ($_GET['attributes'] as $attribute) {
        echo "<th>$attribute</th>";
    }

    echo "</tr>";

    $rowsFetched = false;

    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
        $rowsFetched = true;
        echo "<tr>";
        foreach ($row as $column) {
            echo "<td>$column</td>";
        }
        echo "</tr>";
    }

    echo "</table>";

    if (!$rowsFetched) {
        echo "<p style='color: blue;'>No tuple found.</p>";
    }
}

if (isset($_GET['searchSubmit'])) {
    if (connectToDB()) {
        if (array_key_exists('selectedTable', $_GET)) {
            handleSelectRequest();
        }

        disconnectFromDB();
    }
}
?>
</body>
</html>




