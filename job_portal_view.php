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
    // Start the session
    session_start();
    ?>
  <html>
    <head>
        <title>Job Portal</title>
    </head>
        <h2>Display Selected Attributes</h2>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['tables'])) {
                // Process submitted form data
                $selectedTable = $_GET['tables'];

                // Replace this with your logic to fetch attributes dynamically based on the selected table
                // Sample attributes for demonstration purposes
                $tableAttributes = [
                    "UserLogInfo" => ["UserName", "PassWord"],
                    "Users" => ["UserName", "Name", "EmailAddress", "PhoneNumber", "Description"],
                    "Companies" => ["CompanyId", "CompanyName", "Address"],
                    "Recruiters" => ["UserName", "CompanyId"],
                    "JobSeekers" => ["UserName"],
                    "JobPosts" => ["JobPostId", "RecruiterId", "Title", "Location", "Salary", "PostDate", "JobType", "Description", "Deadline", "Requirements", "NumOfApplications"],
                    "Resumes" => ["Resume", "JobSeekerId"],
                    "Applications" => ["ApplicationId", "RecruiterId", "JobPostId", "CreateDate", "CoverLetter", "Resume", "Status", "ApplyDate"],
                    "ScheduledInterviews" => ["InterviewId", "JobPostId", "Location", "InterviewMode", "DateTime", "TimeZone"],
                    "Applications_ScheduledInterviews" => ["InterviewId", "ApplicationId"],
                    "Interviewers_Attend" => ["InterviewerId", "InterviewId", "Name", "ContactNum"],
                    "LocationDetails" => ["PostalCode", "City", "Province"],
                    "CareerFairs" => ["EventId", "EventName", "PostalCode", "Location", "EventDate"],
                    "Companies_CareerFairs" => ["CompanyId", "EventId"],
                    "JobSeekers_CareerFairs" => ["JobSeekerId", "EventId"]           
                ];

                // Display checkboxes for attributes of the selected table
                if (array_key_exists($selectedTable, $tableAttributes)) {
                    echo "<form method='get' action='job_portal_view.php'>";
                    echo "<input type='hidden' name='selectedTable' value='$selectedTable'>";
                    echo "<label for='attributes[]'>Choose attribute(s) for $selectedTable:</label><br>";
                    foreach ($tableAttributes[$selectedTable] as $attribute) {
                        echo "<input type='checkbox' id='$attribute' name='attributes[]' value='$attribute'>";
                        echo "<label for='$attribute'>$attribute</label><br>";
                    }
                    echo "<br><input type='submit' value='Submit' name='searchSubmit'>";
                    echo "</form>";

                    echo "<form method='get' action='job_portal_view.php'>";
                    echo "<input type='submit' value='Go back to select table'>";
                    echo "</form>";
                } else {
                    echo "<p>Selected table does not exist or has no attributes.</p>";
                }
            } else {
                // Display dropdown to select table
                ?>
                <form method="get" action="job_portal_view.php">
                    <label for="tables">Choose a table:</label>
                    <select id="tables" name="tables">
                        <option value="UserLogInfo">UserLogInfo</option>
                        <option value="Users">Users</option>
                        <option value="Companies">Companies</option>
                        <option value="Recruiters">Recruiters</option>
                        <option value="JobSeekers">Job Seekers</option>
                        <option value="JobPosts">Job Posts</option>
                        <option value="Resumes">Resumes</option>
                        <option value="Applications">Applications</option>
                        <option value="ScheduledInterviews">Scheduled Interviews</option>
                        <option value="Applications_ScheduledInterviews">Applications and Scheduled Interviews</option>
                        <option value="Interviewers_Attend">Interviewers Attend</option>
                        <option value="LocationDetails">Location Details</option>
                        <option value="CareerFairs">Career Fairs</option>
                        <option value="Companies_CareerFairs">Companies and Career Fairs</option>
                        <option value="JobSeekers_CareerFairs">JobSeekers and Career Fairs</option>
                        <!-- Add more table options here -->
                    </select>
                    <br><br>
                    <input type="submit" value="Submit">
                </form>
                <?php
            }
            ?>
            <a href="job_portal.php"><button>Go Back to Main Page</button></a>


        <?php
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

        function handleSelectRequest() {
            global $db_conn;

            if (!isset($_GET["attributes"]) || count($_GET["attributes"]) === 0) {
                echo ("<p style='color: red;'>No attribute selected.</p>");
                return; 
            }
            $attributes = implode(", ", $_GET['attributes']);

            $result = executePlainSQL("SELECT ". $attributes . " FROM ". $_GET["selectedTable"]);

            echo "<table border='1'><tr>";

            // Display table headers based on selected attributes
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