<?php 
    session_start();

    $success = True; //keep track of errors so it redirects the page only if there are no errors
    $db_conn = NULL; // edit the login credentials in connectToDB()
    $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);

    function debugAlertMessage($message) {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
      }

    function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
        //echo "<br>running ".$cmdstr."<br>";
        global $db_conn, $success;

        $statement = OCIParse($db_conn, $cmdstr);
        //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
            echo htmlentities($e['message']);
            $success = False;
          }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        }

        return $statement;
    }

    function executeBoundSQL($cmdstr, $list) {
        /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
    In this case you don't need to create the statement several times. Bound variables cause a statement to only be
    parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
    See the sample code below for how this function is used */

        global $db_conn, $success;
        $statement = OCIParse($db_conn, $cmdstr);

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
                OCIBindByName($statement, $bind, $val);
                unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                echo htmlentities($e['message']);
                echo "<br>";
                $success = False;
            }
        }
    }

    function printResult($result) { //prints results from a select statement
        echo "<br>Retrieved data from table demoTable:<br>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    function connectToDB() {
        global $db_conn;

        // Your username is ora_(CWL_ID) and the password is a(student number). For example,
        // ora_platypus is the username and a12345678 is the password.
        $db_conn = OCILogon("ora_xli2801", "a80002512", "dbhost.students.cs.ubc.ca:1522/stu");

        if ($db_conn) {
            debugAlertMessage("Database is Connected");
            return true;
        } else {
            debugAlertMessage("Cannot connect to Database");
            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
            return false;
        }
    }

    function disconnectFromDB() {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }
    if (isset($_POST['loginSubmit'])) {
        handlePOSTRequest();
    } 

    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('loginQueryRequest', $_POST)) {
                handleLoginRequest();
            }

            disconnectFromDB();
        }
    }

    function handleLoginRequest() {
        global $db_conn;

        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = executePlainSQL("SELECT COUNT(*) FROM UserLogInfo WHERE UserName = '$username' AND PassWord = '$password'");

        if (($row = oci_fetch_row($result)) != false) {
            $count = $row[0];
            if ($count == 1) {
                echo("success");
                $type = executePlainSQL("SELECT COUNT(*) FROM JobSeekers WHERE UserName = '$username'");
                if (($row = oci_fetch_row($type)) != false) {
                    $type = $row[0];
                    if ($type == 1) {
                        header('Location: hello.php?username='. $username);
                        exit();
                    } else {
                        header('Location: recruiter.php?username='. $username);
                        exit();
                    }
                }else {
                    echo("login failed...");
                    $_SESSION['error_message'] = "Invalid username or password";
                    header('Location: job_portal.php');
                    exit();
                }
            } else {
                echo("login failed...1");
                $_SESSION['error_message'] = "Invalid username or password";
                header('Location: job_portal.php');
                exit();
            }
        }else {
            echo("login failed...2");
            $_SESSION['error_message'] = "Invalid username or password";
            header('Location: job_portal.php');
            exit();
        }
    }
?>