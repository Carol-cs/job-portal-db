<!-- Test Oracle file for UBC CPSC304
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  Modified by Jason Hall (23-09-20)
  This file shows the very basics of how to execute PHP commands on Oracle.
  Specifically, it will drop a table, create a table, insert values update
  values, and then query for values
  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up All OCI commands are
  commands to the Oracle libraries. To get the file to work, you must place it
  somewhere where your Apache server can run it, and you must rename it to have
  a ".php" extension. You must also change the username and password on the
  oci_connect below to be your ORACLE username and password
-->

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


<?php
session_start();

// Check if the username is provided in the URL parameters
if (isset($_GET['username']) && $_GET['username']!='') {

    $_SESSION['username'] = $_GET['username'];
}
// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    // Display the username
    echo "Hi! {$_SESSION['username']}";
} else {
    echo "<p style='color: red;'>Invalid Access</p>";
    header("Location: job_portal.php");
    exit();
}
?>


<html>

<head>
    <title>CPSC 304 PHP/Oracle Demonstration</title>
</head>

<body>
<h2>LogOut</h2>
<p>You can log out to reset the table</p>

<form method="POST" action="logout.php">
    <!-- "action" specifies the file or page that will receive the form data for processing. As with this example, it can be this same file. -->
    <input type="hidden" id="logOutRequest" name="logOutRequest">
    <p><input type="submit" value="Log Out" name="LogOut"></p>
</form>

<hr>

<h2>Recruiter Reviews Applications</h2>
<form method="GET" action="recruiter.php">
    <input type="hidden" id="displayApplicationsRequest" name="displayApplicationsRequest">
    <input type="submit" value="Review" name="displayApplications"></p>
</form>

<?php
// all functions for Recruiters to review applications and create/edit/delete interviews

function displayApplications($result)
{
    global $db_conn;

    $rowsFetched = false;

    echo "<br>Applications:<br>";
    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
    echo "<input type='hidden' id='saveStatusRequest' name='saveStatusRequest'>";
    echo "<table border='1'>";
    echo "<tr>
                <th>Application ID</th>
                <th>Job Title</th>
                <th>Job Seeker Name</th>
                <th>Resume</th>
                <th>Cover Letter</th>
                <th>Status</th>
                <th>Create Date</th>
                <th>Apply Date</th>
				<th>Action</th>
              </tr>";

    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        $rowsFetched = true;
        $applicationId = $row["APPLICATIONID"];
        $coverLetter = $row["COVERLETTER"] ?? '';
        if ($coverLetter !== ''){
            $coverLetterLink = "<a href='{$coverLetter}'>Cover Letter</a>";
        } else{
            $coverLetterLink = "";
        }

        // create links for Resume and Cover Letter
        $resumeLink = "<a href='{$row["RESUME"]}'>Resume</a>";


        $statusOptions = array('Under Review', 'Interviewing', 'Accepted', 'Rejected');
        $currentStatus = $row["STATUS"];
        echo "<tr>
                    <td>{$applicationId}</td>
                    <td>{$row["JOBTITLE"]}</td>
                    <td>{$row["JOBSEEKERNAME"]}</td>
                    <td>{$resumeLink}</td>
                    <td>{$coverLetterLink}</td>
                    <td>
                        <select name='statusList[{$applicationId}]'>";

        foreach ($statusOptions as $option) {
            $selected = ($option == $currentStatus) ? 'selected' : '';
            echo "<option value='{$option}' {$selected}>{$option}</option>";
        }
        echo "</select>
                    </td>
                    <td>{$row["CREATEDATE"]}</td>
                    <td>{$row["APPLYDATE"]}</td>
					<td>
					<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
						<input type='hidden' id='scheduleInterviewRequest' name='scheduleInterviewRequest'>
						<button type='submit' name='scheduleInterview' value='{$applicationId}'>Schedule Interview</button>
					</form>
                </td>
					
                  </tr>";
    }

    if (!$rowsFetched){
        echo "<p style='color: blue;'>No applications found.</p>";
    }

    echo "</table>";
    echo "<p><input type='submit' value='Save' name='saveStatus'></p>";
    echo "</form>";

}

function displayInterviews($result, $interviewCount, $applicationId){
    global $db_conn;

    echo "<h2>Scheduled Interviews</h2>";
    echo "<table border='1'>";
    echo "<tr>
				<th>Interview Id</th>
				<th>Location</th>
				<th>Interview Mode</th>
				<th>Date and Time</th>
				<th>Timezone</th>
				<th>Interviewer Info</th>
				<th>Actions</th>
			  </tr>";

    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        $dateString = $row["FORMATTEDDATETIME"];
        $date = new DateTime($dateString);
        $formattedDateString = $date->format('F j, Y g:i A');


        echo "<tr>
					<td>{$row["INTERVIEWID"]}</td>
					<td>{$row["INTERVIEWLOCATION"]}</td>
					<td>{$row["INTERVIEWMODE"]}</td>
					<td>{$formattedDateString}</td>
					<td>{$row["INTERVIEWTIMEZONE"]}</td>
					<td>
					 ID: {$row["INTERVIEWERID"]}<br>
					 Name: {$row["NAME"]}<br>
					 Contact: {$row["CONTACTNUM"]}<br>

					
					</td>
					<td>
						<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
							<input type='hidden' id='editInterviewRequest' name='editInterviewRequest'>
							<button type='submit' name='editInterview'  value='" . htmlspecialchars(json_encode($row)) . "'>Edit</button>
						</form>
						<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
							<input type='hidden' id='deleteInterviewRequest' name='deleteInterviewRequest'>
							<button type='submit' name='deleteInterview' value='{$row["INTERVIEWID"]}'>Delete</button>
						</form>
					</td>
				  </tr>";
    }


    echo "</table>";


    echo "<p>{$interviewCount} " . ($interviewCount > 1 ? 'interviews' : 'interview') . "</p>";
    $arrForCreateInterview=executePlainSQL("SELECT ApplicationId, JobPostId FROM Applications WHERE ApplicationId='{$applicationId}'");
    $arrForCreateInterview = OCI_Fetch_Array($arrForCreateInterview, OCI_ASSOC);



    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
			   <input type='hidden' id='createNewInterviewRequest' name='createNewInterviewRequest'>
			   <button type='submit' name='createNewInterview'   value='" . htmlspecialchars(json_encode($arrForCreateInterview)) . "'>Create New Interview</button>
		</form>";





}



function handleDisplayApplicationsRequest()
{
    global $db_conn;
    $username=$_SESSION["username"];
    $result = executePlainSQL(
        "SELECT
                Applications.ApplicationId,
                JobPosts.Title AS JobTitle,
                Users.Name AS JobSeekerName,
                Applications.Resume,
                Applications.CoverLetter,
                Applications.Status,
                Applications.CreateDate,
                Applications.ApplyDate
            FROM
                Applications,
                JobPosts,
                Resumes,
                Users
            WHERE
                Applications.JobPostId = JobPosts.JobPostId
                AND Applications.RecruiterId = '$username'
                AND Resumes.Resume = Applications.Resume
                AND Resumes.JobSeekerId = Users.UserName
				AND Applications.Status <> 'Incomplete application'");
    oci_commit($db_conn);
    displayApplications($result);
}

function handleSaveStatusRequest()
{
    global $db_conn, $success;

    $statusList = $_POST['statusList'];
    foreach ($statusList as $applicationId => $newStatus) {

        $tuple = array(
            ":newStatus" => $newStatus,
            ":applicationId" => $applicationId
        );

        $alltuples = array(
            $tuple
        );

        executeBoundSQL( "UPDATE Applications SET Status = :newStatus WHERE ApplicationId = :applicationId", $alltuples);
    }
    oci_commit($db_conn);

    if ($success) {
        echo "<p style='color: green;'>Saved!</p>";
    } else {
        echo "<p style='color: red;'>Unable to save.</p>";
    }

}

function handleScheduleInterviewRequest($applicationId)
{	global $db_conn;
    echo "Scheduling interview for Application ID: {$applicationId}";
    $result = executePlainSQL(
        "SELECT
				ASI.ApplicationId,
				SI.InterviewId,
				SI.Location AS InterviewLocation,
				SI.InterviewMode,
				TO_CHAR(SI.DateTime, 'YYYY-MM-DD\"T\"HH24:MI') AS FormattedDateTime,
				SI.TimeZone AS InterviewTimeZone,
				IA.InterviewerId,
				IA.Name,
				IA.ContactNum
			FROM
				Applications_ScheduledInterviews ASI,
				ScheduledInterviews SI,
				Interviewers_Attend IA
			WHERE
				ASI.InterviewId = SI.InterviewId
				AND IA.InterviewId = SI.InterviewId
				AND ASI.ApplicationId = $applicationId");

    $countInterviews = executePlainSQL(
        "SELECT COUNT(InterviewId) AS InterviewCount
			FROM Applications_ScheduledInterviews
			WHERE ApplicationId = $applicationId
			GROUP BY
				ApplicationId");
    $value = OCI_Fetch_Array($countInterviews, OCI_ASSOC);
    if ($value == null){
        displayInterviews($result, 0, $applicationId);

    } else{
        displayInterviews($result, $value["INTERVIEWCOUNT"], $applicationId);
    }


}

function handleEditInterviewRequest($row)
{	global $db_conn;
    $rowData = json_decode(htmlspecialchars_decode($row), true);


    echo "editing interview for rows: {$rowData["INTERVIEWID"]}";

    echo "<label>Interview ID: {$rowData['INTERVIEWID']}</label>";

    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
    echo "<input type='hidden' name='saveEditInterviewRequest' value='{$rowData['INTERVIEWID']}'>";

    echo "<label for='interviewLocation'>Location:</label>
              <input type='text' name='interviewLocation' value='{$rowData['INTERVIEWLOCATION']}'><br>";

    echo "<label for='interviewMode'>Interview Mode:</label>
              <select name='interviewMode'>
                <option value='In-Person' " . ($rowData['INTERVIEWMODE'] == 'In-Person' ? 'selected' : '') . ">In-Person</option>
                <option value='Online' " . ($rowData['INTERVIEWMODE'] == 'Online' ? 'selected' : '') . ">Online</option>
              </select><br>";

    echo "<label for='datetime'>Date and Time: </label>

				<input
				type='datetime-local'
				id='interviewDatetime'
				name='interviewDatetime'
				value='{$rowData['FORMATTEDDATETIME']}'><br>";

    echo "<label for='interviewTimezone'>Timezone:</label>
              <input type='text' name='interviewTimezone' value='{$rowData['INTERVIEWTIMEZONE']}'><br>";


    // display interviewer info
    echo "<label for='interviewerId'>Interviewer ID:</label>
              <input type='text' name='interviewerId' value='{$rowData['INTERVIEWERID']}'><br>";

    echo "<label for='interviewerName'>Interviewer Name:</label>
              <input type='text' name='interviewerName' value='{$rowData['NAME']}'><br>";

    echo "<label for='interviewerContactNum'>Interviewer Contact Number:</label>
              <input type='text' name='interviewerContactNum' value='{$rowData['CONTACTNUM']}'><br>";

    echo "<button type='submit' name='saveEditInterview' value='{$rowData['INTERVIEWID']}'>Save Changes</button>";
    echo "</form>";

}

function handleDeleteInterviewRequest($interviewId)
{	global $db_conn, $success;
    echo "deleting interview for interview ID: {$interviewId}";
    executePlainSQL("DELETE FROM ScheduledInterviews WHERE InterviewId = {$interviewId}");
    oci_commit($db_conn);
    if ($success) {
        echo "<p style='color: green;'>Interview was deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Fail to delete the interview.</p>";
    }
}


function handleSaveEditInterviewRequest($interviewId)
{
    global $db_conn, $success;


    //Getting the values from user and insert data into the table
    $tuple1 = array(
        ":bind1" => $_POST['interviewLocation'],
        ":bind2" => $_POST['interviewMode'],
        ":bind3" => $_POST['interviewDatetime'],
        ":bind4" => $_POST['interviewTimezone']
    );

    $alltuples1 = array(
        $tuple1
    );

    $tuple2 = array(
        ":bind5" => $_POST['interviewerId'],
        ":bind6" => $_POST['interviewerName'],
        ":bind7" => $_POST['interviewerContactNum']
    );

    $alltuples2 = array(
        $tuple2
    );



    executeBoundSQL("
		UPDATE ScheduledInterviews
		SET Location = :bind1,
			InterviewMode =  :bind2,
			DateTime = TO_DATE(:bind3, 'YYYY-MM-DD\"T\"HH24:MI'),
			TimeZone = :bind4
		WHERE InterviewId = {$interviewId}
	", $alltuples1);
    executeBoundSQL("
			UPDATE Interviewers_Attend
			SET Name = :bind6,
				ContactNum = :bind7
			WHERE InterviewId = {$interviewId} AND InterviewerId = :bind5
		", $alltuples2);

    oci_commit($db_conn);
    if ($success) {
        echo "<p style='color: green;'>Interview was edited successfully!</p>";
    } else {
        echo "<p style='color: red;'>Fail to edit the interview.</p>";
    }

}

function handleCreateNewInterviewRequest($arr) {
    echo "<h2>Create New Interview</h2>";
    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
    echo "<input type='hidden' name='saveNewInterviewRequest' id='saveNewInterviewRequest'>";

    // Add form fields for new interview details
    echo "<label for='interviewLocation'>Location:</label>
			  <input type='text' name='interviewLocation' required><br>";

    echo "<label for='interviewMode'>Interview Mode:</label>
			  <select name='interviewMode' required>
				  <option value='In-Person'>In-Person</option>
				  <option value='Online'>Online</option>
			  </select><br>";

    echo "<label for='interviewDatetime'>Date and Time:</label>
			  <input type='datetime-local' name='interviewDatetime' required><br>";

    echo "<label for='interviewTimezone'>Timezone:</label>
			  <input type='text' name='interviewTimezone' required><br>";

    // Add form fields for interviewer details
    echo "<label for='interviewerName'>Interviewer Name:</label>
			  <input type='text' name='interviewerName' required><br>";

    echo "<label for='interviewerContactNum'>Interviewer Contact Number:</label>
			  <input type='text' name='interviewerContactNum'><br>";

    echo "<button type='submit' name='saveNewInterview' value='{$arr}'>Save New Interview</button>";
    echo "</form>";
}


function handleSaveNewInterviewRequest($arr) {
    global $db_conn, $success;
    $arrData = json_decode(htmlspecialchars_decode($arr), true);

    $applicationId = $arrData['APPLICATIONID'];
    $interviewLocation = $_POST['interviewLocation'];
    $interviewMode = $_POST['interviewMode'];
    $interviewDatetime = $_POST['interviewDatetime'];
    $interviewTimeZone = $_POST['interviewTimezone'];
    $interviewerName = $_POST['interviewerName'];
    $interviewerContactNum = $_POST['interviewerContactNum'];

    $jobPostId = $arrData['JOBPOSTID'];


    executePlainSQL("INSERT INTO ScheduledInterviews VALUES (InterviewId_Sequence.nextval, '$jobPostId', '$interviewLocation', '$interviewMode', TO_DATE('$interviewDatetime', 'YYYY-MM-DD\"T\"HH24:MI'), '$interviewTimeZone')");
    $result = executePlainSQL("SELECT InterviewId_Sequence.currval FROM dual");

    while ($interviewIdRow = OCI_Fetch_Array($result, OCI_ASSOC)){
        $interviewId = $interviewIdRow['CURRVAL'];

        executePlainSQL("INSERT INTO Interviewers_Attend VALUES ('$interviewId', InterviewerId_Sequence.nextval, '$interviewerName', '$interviewerContactNum')");
        executePlainSQL("INSERT INTO Applications_Scheduledinterviews VALUES ('$interviewId', '$applicationId')");
    }

    oci_commit($db_conn);
    if ($success) {
        echo "<p style='color: green;'>Interview was scheduled successfully!</p>";
    } else {
        echo "<p style='color: red;'>Fail to schedule the interview.</p>";
    }



}

// all routes checker for recruiter reviewing application and scedule interviews
if (isset($_POST['saveNewInterview']) || isset($_POST['createNewInterview']) || isset($_POST['saveEditInterview'])|| isset($_POST['editInterview']) || isset($_POST['deleteInterview']) || isset($_POST['scheduleInterview']) || isset($_POST['saveStatus'], $_POST['statusList']) ) {
    handlePOSTRequest();
} else if (isset($_GET['displayApplications']))  {
    handleGETRequest();
}


?>


<hr>
<h2>Recruiter View Job Posts</h2>
<form method="GET" action="recruiter.php">
    <input type="hidden" id="displayJobPostsRequest" name="displayJobPostsRequest">
    <input type="submit" value="View Job Posts" name="displayJobPosts"></p>
</form>


<?php

function displayJobPosts($result){

    echo "<h2>Job Posts</h2>";
    echo "<div>";
    $rowsFetched = false; // used to track if have result
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        $rowsFetched = true;
        echo "<div style='border: 1px solid #ccc; margin-bottom: 20px; padding: 10px;'>
						<h3>{$row["JOBTITLE"]}</h3>
						<p><strong>Company:</strong> {$row["COMPANYNAME"]}</p>
						<p><strong>Location:</strong> {$row["JOBLOCATION"]}</p>
						<p><strong>Job Type:</strong> {$row["JOBTYPE"]}</p>
						<p><strong>Salary:</strong> {$row["SALARY"]}</p>
						<p><strong>Post Date:</strong> {$row["POSTDATE"]}</p>
						<p><strong>Description:</strong> {$row["DESCRIPTION"]}</p>
						<p><strong>Requirements:</strong> {$row["REQUIREMENTS"]}</p>
						<p><strong>Deadline:</strong> {$row["DEADLINE"]}</p>
						<p><strong>Num of Applications:</strong> {$row["NUMOFAPPLICATIONS"]}</p>
						<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
						<input type='hidden' id='deleteJobPostsRequest' name='deleteJobPostsRequest'>
						<button type='submit' name='deleteJobPosts' value='{$row["JOBPOSTID"]}'>Delete</button>
					</form>
					</div>";
    }
    if (!$rowsFetched) {
        echo "<p style='color: blue;'>No job posts found.</p>";
    }

    echo "</div>";
}

function handleDisplayJobPostsRequest(){
    global $db_conn;
    $username=$_SESSION["username"];
    $result = executePlainSQL(
        "SELECT
					JobPosts.JobPostId,
					JobPosts.Title As JobTitle,
					Companies.CompanyName,
					JobPosts.Location As JobLocation,
					JobPosts.JobType,
					JobPosts.Salary,
					JobPosts.PostDate,
					JobPosts.Description,
					JobPosts.Requirements,
					JobPosts.Deadline,
					JobPosts.NumOfApplications
				
				FROM
					JobPosts,
					Recruiters,
					Companies
				WHERE
					JobPosts.RecruiterId = '$username'
					AND JobPosts.RecruiterId = Recruiters.UserName
					AND Recruiters.CompanyId = Companies.CompanyId
				");
    oci_commit($db_conn);
    displayJobPosts($result);
}

function handleDeleteJobPostsRequest($jobPostId){
    global $db_conn, $success;
    echo "deleting interview for interview ID: {$jobPostId}";
    executePlainSQL("DELETE FROM JobPosts WHERE JobPostId = {$jobPostId}");
    oci_commit($db_conn);
    if ($success){
        echo "<p style='color: green;'>The job post was deleted successfully!</p>";

    } else{
        echo "<p style='color: green;'>Fail to delete the job post</p>";
    }
}


// all routes checker for recruiter viewing job posts
if (isset($_POST['deleteJobPosts'])) {
    handlePOSTRequest();
} else if (isset($_GET['displayJobPosts']))  {
    handleGETRequest();
}




?>

<hr>
<h2>Recruiter Create Job Posts</h2>
<form method="POST" action="recruiter.php">
    <label for="jobTitle">Job Title:</label>
    <input type="text" name="jobTitle" required><br>

    <label for="jobLocation">Job Location:</label>
    <input type="text" name="jobLocation"><br>

    <label for="jobType">Job Type:</label>
    <input type="text" name="jobType" required><br>

    <label for="salary">Salary:</label>
    <input type="text" name="salary"><br>

    <label for="description">Description:</label>
    <textarea name="description" rows="4" required></textarea><br>

    <label for="requirements">Requirements:</label>
    <textarea name="requirements" rows="4"></textarea><br>

    <label for="deadline">Deadline:</label>
    <input type="date" name="deadline" required><br>
    <input type="hidden" id="createJobPostsRequest" name="createJobPostsRequest">
    <input type="submit" value="Create Job Posts" name="createJobPosts"></p>
</form>

<hr>

<?php


function handleCreateJobPostsRequest(){
    global $db_conn, $success;

    // Retrieve values from the form
    $deadline = $_POST['deadline'];

    // Assign today's date for PostDate
    $postDate = date('Y-m-d');



    $tuple = array(
        ":bind1" => $_SESSION["username"],
        ":bind2" => $_POST['jobTitle'],
        ":bind3" => $_POST['jobLocation'],
        ":bind4" => $_POST['salary'],
        ":bind5" => $_POST['jobType'],
        ":bind6" => $_POST['description'],
        ":bind7" => $_POST['requirements']
    );

    $alltuples = array(
        $tuple
    );

    // Insert the job post into the database
    executeBoundSQL("
				INSERT INTO JobPosts
				VALUES (JobPostId_Sequence.nextval,:bind1, :bind2, :bind3,  :bind4, TO_DATE('$postDate', 'YYYY-MM-DD'), :bind5, :bind6, TO_DATE('$deadline', 'YYYY-MM-DD'),:bind7, 0)
			", $alltuples);

    if ($success){
        echo "<p style='color: green;'>Job post was created successfully!</p>";
    } else{
        echo "<p style='color: red;'>Fail to create job post</p>";
    }

    oci_commit($db_conn);

}



// all routes checker for recruiter viewing job posts
if ( isset($_POST['createJobPosts'])) {
    handlePOSTRequest();
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





// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest()
{
    var_dump($_POST);
    if (connectToDB()) {
        if (array_key_exists('saveNewInterviewRequest', $_POST) && array_key_exists('saveNewInterview', $_POST)) {
            handleSaveNewInterviewRequest($_POST['saveNewInterview']);
        }else if (array_key_exists('createNewInterviewRequest', $_POST) && array_key_exists('createNewInterview', $_POST)) {
            handleCreateNewInterviewRequest($_POST['createNewInterview']);
        }else if (array_key_exists('saveEditInterviewRequest', $_POST) && array_key_exists('saveEditInterview', $_POST)) {
            handleSaveEditInterviewRequest($_POST['saveEditInterview']);
        }else if (array_key_exists('editInterviewRequest', $_POST) && array_key_exists('editInterview', $_POST)) {
            handleEditInterviewRequest($_POST['editInterview']);
        }else if (array_key_exists('deleteInterviewRequest', $_POST) && array_key_exists('deleteInterview', $_POST)) {
            handleDeleteInterviewRequest($_POST['deleteInterview']);
        }else if (array_key_exists('scheduleInterviewRequest', $_POST) && array_key_exists('scheduleInterview', $_POST)) {
            handleScheduleInterviewRequest($_POST['scheduleInterview']);
        } else if (array_key_exists('saveStatusRequest', $_POST)  && array_key_exists('saveStatus', $_POST)) {
            handleSaveStatusRequest();
        } else if (array_key_exists('deleteJobPostsRequest', $_POST)  && array_key_exists('deleteJobPosts', $_POST)) {
            handleDeleteJobPostsRequest($_POST['deleteJobPosts']);
        } else if (array_key_exists('createJobPostsRequest', $_POST)  && array_key_exists('createJobPosts', $_POST)) {
            handleCreateJobPostsRequest($_POST['createJobPosts']);
        }

        disconnectFromDB();
    }
}


// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest()
{
    if (connectToDB()) {
        if (array_key_exists('displayApplications', $_GET)){
            handleDisplayApplicationsRequest();
        }elseif (array_key_exists('displayJobPosts', $_GET)){
            handleDisplayJobPostsRequest();
        }
        disconnectFromDB();
    }
}




// End PHP parsing and send the rest of the HTML content
?>
</body>

</html>
