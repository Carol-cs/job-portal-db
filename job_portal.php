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

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="job_portal.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <?php
        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function handleResetRequest() {
            global $db_conn;
            global $success;

            $password1 = password_hash("johnpassword123", PASSWORD_DEFAULT);
            $password2 = password_hash("janepassword456!", PASSWORD_DEFAULT);
            $password3 = password_hash("michaelpassword789", PASSWORD_DEFAULT);
            $password4 = password_hash("emilypassword123", PASSWORD_DEFAULT);
            $password5 = password_hash("williampassword456", PASSWORD_DEFAULT);
            $password6 = password_hash("oliviapassword789", PASSWORD_DEFAULT);
            $password7 = password_hash("jamespassword123", PASSWORD_DEFAULT);
            $password8 = password_hash("avapassword456", PASSWORD_DEFAULT);
            $password9 = password_hash("robertpassword789", PASSWORD_DEFAULT);
            $password10 = password_hash("sophiapassword123", PASSWORD_DEFAULT);
            
            $sqlQueries = [
                "DROP TABLE JOBSEEKERS_CAREERFAIRS",
                "DROP TABLE COMPANIES_CAREERFAIRS",
                "DROP TABLE CAREERFAIRS",
                "DROP TABLE LOCATIONDETAILS",
                "DROP TABLE INTERVIEWERS_ATTEND",
                "DROP TABLE APPLICATIONS_SCHEDULEDINTERVIEWS",
                "DROP TABLE SCHEDULEDINTERVIEWS",
                "DROP TABLE APPLICATIONS",
                "DROP TABLE RESUMES",
                "DROP TABLE JOBPOSTS",
                "DROP TABLE JOBSEEKERS",
                "DROP TABLE RECRUITERS",
                "DROP TABLE COMPANIES",
                "DROP TABLE USERS",
                "DROP TABLE USERLOGINFO",
                "DROP SEQUENCE CompanyId_Sequence",
                "DROP SEQUENCE JobPostId_Sequence",
                "DROP SEQUENCE ApplicationId_Sequence",
                "DROP SEQUENCE InterviewId_Sequence",
                "DROP SEQUENCE EventId_Sequence",
                
                "CREATE TABLE UserLogInfo (
                    UserName VARCHAR(100) PRIMARY KEY,
                    PassWord VARCHAR(100) NOT NULL
                )",
                
                "CREATE TABLE Users (
                    UserName VARCHAR(100) PRIMARY KEY,
                    Name VARCHAR(100) NOT NULL,
                    EmailAddress VARCHAR(100) NOT NULL UNIQUE,
                    PhoneNumber VARCHAR(20) UNIQUE,
                    Description VARCHAR(4000),
                    FOREIGN KEY (UserName) REFERENCES UserLogInfo ON DELETE CASCADE
                )",
            
                "CREATE SEQUENCE CompanyId_Sequence START WITH 6 INCREMENT BY 1",
                
                "CREATE TABLE Companies (
                    CompanyId INTEGER PRIMARY KEY,
                    CompanyName VARCHAR(100) NOT NULL,
                    Address VARCHAR(100),
                    UNIQUE (CompanyName, Address)
                )",
            
                "CREATE TABLE Recruiters (
                    UserName VARCHAR(100) PRIMARY KEY,
                    CompanyId INTEGER,
                    FOREIGN KEY (UserName) REFERENCES Users ON DELETE CASCADE,
                    FOREIGN KEY (CompanyId) REFERENCES Companies ON DELETE CASCADE
                )",
            
                "CREATE TABLE JobSeekers (
                    UserName VARCHAR(100) PRIMARY KEY,
                    FOREIGN KEY (UserName) REFERENCES Users ON DELETE CASCADE
                )",
            
                "CREATE SEQUENCE JobPostId_Sequence START WITH 6 INCREMENT BY 1",
                
                "CREATE TABLE JobPosts (
                    JobPostId INTEGER PRIMARY KEY,
                    RecruiterId VARCHAR(100),
                    Title VARCHAR(100) NOT NULL,
                    Location VARCHAR(100),
                    Salary INTEGER,
                    PostDate DATE NOT NULL,
                    JobType VARCHAR(100) NOT NULL,
                    Description VARCHAR(4000) NOT NULL,
                    Deadline DATE NOT NULL,
                    Requirements VARCHAR(4000),
                    NumOfApplications INTEGER NOT NULL,
                    FOREIGN KEY (RecruiterId) REFERENCES Recruiters(UserName) ON DELETE CASCADE
                )",
                
                "CREATE TABLE Resumes (
                    Resume VARCHAR(4000) PRIMARY KEY,
                    JobSeekerId VARCHAR(100) NOT NULL,
                    FOREIGN KEY (JobSeekerId) REFERENCES JobSeekers(UserName)
                )",
            
                "CREATE SEQUENCE ApplicationId_Sequence START WITH 6 INCREMENT BY 1",
                
                "CREATE TABLE Applications (
                    ApplicationId INTEGER PRIMARY KEY,
                    RecruiterId VARCHAR(100),
                    JobPostId INTEGER,
                    CreateDate DATE NOT NULL,
                    CoverLetter VARCHAR(4000),
                    Resume VARCHAR(4000) NOT NULL,
                    Status VARCHAR(100) NOT NULL,
                    ApplyDate DATE,
                    FOREIGN KEY (RecruiterId) REFERENCES Recruiters(UserName),
                    FOREIGN KEY (JobPostId) REFERENCES JobPosts(JobPostId) ON DELETE SET NULL,
                    FOREIGN KEY (Resume) REFERENCES Resumes(Resume)
                )",
            
                "CREATE SEQUENCE InterviewId_Sequence START WITH 6 INCREMENT BY 1",
                
                "CREATE TABLE ScheduledInterviews (
                    InterviewId INTEGER PRIMARY KEY,
                    JobPostId INTEGER,
                    Location VARCHAR(255) NOT NULL,
                    InterviewMode CHAR(10) NOT NULL,
                    DateTime DATE NOT NULL,
                    TimeZone VARCHAR(10) NOT NULL,
                    FOREIGN KEY (JobPostId) REFERENCES JobPosts(JobPostId) ON DELETE SET NULL
                )",
            
                "CREATE TABLE Applications_ScheduledInterviews (
                    InterviewId INTEGER,
                    ApplicationId INTEGER,
                    PRIMARY KEY (InterviewId, ApplicationId),
                    FOREIGN KEY (InterviewId) REFERENCES ScheduledInterviews ON DELETE CASCADE,
                    FOREIGN KEY (ApplicationId) REFERENCES Applications ON DELETE CASCADE
                )",
                
                "CREATE TABLE Interviewers_Attend(
                    InterviewerId INTEGER,
                    InterviewId INTEGER,
                    Name VARCHAR(100) NOT NULL,
                    ContactNum VARCHAR(100),
                    PRIMARY KEY (InterviewerId, InterviewId),
                    FOREIGN KEY (InterviewId) REFERENCES ScheduledInterviews ON DELETE CASCADE
                )",
            
                "CREATE TABLE LocationDetails(
                    PostalCode VARCHAR(10) PRIMARY KEY,
                    City VARCHAR(100) NOT NULL,
                    Province VARCHAR(100) NOT NULL
                )",
            
                "CREATE SEQUENCE EventId_Sequence START WITH 6 INCREMENT BY 1",
                
                "CREATE TABLE CareerFairs (
                    EventId INTEGER PRIMARY KEY,
                    EventName VARCHAR(100) NOT NULL,
                    PostalCode VARCHAR(10) NOT NULL,
                    Location VARCHAR(500) NOT NULL,
                    EventDate DATE NOT NULL,
                    FOREIGN KEY (PostalCode) REFERENCES LocationDetails ON DELETE CASCADE
                )",
            
                "CREATE TABLE Companies_CareerFairs(
                    CompanyId INTEGER,
                    EventId INTEGER,
                    PRIMARY KEY (CompanyId, EventId),
                    FOREIGN KEY (CompanyId) REFERENCES Companies ON DELETE CASCADE,
                    FOREIGN KEY (EventId) REFERENCES CareerFairs ON DELETE CASCADE
                )",
            
                "CREATE TABLE JobSeekers_CareerFairs(
                    JobSeekerId VARCHAR(100),
                    EventId INTEGER,
                    PRIMARY KEY (JobSeekerId, EventId),
                    FOREIGN KEY (JobSeekerId) REFERENCES JobSeekers(UserName) ON DELETE CASCADE,
                    FOREIGN KEY (EventId) REFERENCES CareerFairs ON DELETE CASCADE
                )",
                "INSERT INTO UserLogInfo
                VALUES ('john_doe', '$password1')",
                "INSERT INTO UserLogInfo
                VALUES ('jane_smith', '$password2')",
               "INSERT INTO UserLogInfo
                VALUES ('michael_johnson', '$password3')",
                "INSERT INTO UserLogInfo
                VALUES ('emily_brown', '$password4')",
                "INSERT INTO UserLogInfo
                VALUES ('william_davis', '$password5')",
                "INSERT INTO UserLogInfo
                VALUES ('olivia_wilson', '$password6')",
                "INSERT INTO UserLogInfo
                VALUES ('james_miller', '$password7')",
                "INSERT INTO UserLogInfo
                VALUES ('ava_jones', '$password8')",
                "INSERT INTO UserLogInfo
                VALUES ('robert_lee', '$password9')",
                "INSERT INTO UserLogInfo
                VALUES ('sophia_taylor', '$password10')",
                
                "INSERT INTO Users
                VALUES ('john_doe', 'John Doe', 'john.doe@email.com', '123-456-7890', 'Description of John')",
                "INSERT INTO Users
                VALUES ('jane_smith', 'Jane Smith', 'jane.smith@email.com', '234-567-8901', 'Description of Jane')",
                "INSERT INTO Users
                VALUES ('michael_johnson', 'Michael Johnson', 'michael.johnson@email.com', '345-678-9012', 'Description of Michael')",
                "INSERT INTO Users
                VALUES ('emily_brown', 'Emily Brown', 'emily.brown@email.com', '456-789-0123', 'Description of Emily')",
                "INSERT INTO Users
                VALUES ('william_davis', 'William Davis', 'william.davis@email.com', '567-890-1234', 'Description of William')",
                "INSERT INTO Users
                VALUES ('olivia_wilson', 'Olivia Wilson', 'olivia.wilson@email.com', '678-901-2345', 'Description of Olivia')",
                "INSERT INTO Users
                VALUES ('james_miller', 'James Miller', 'james.miller@email.com', '789-012-3456', 'Description of James')",
                "INSERT INTO Users
                VALUES ('ava_jones', 'Ava Jones', 'ava.jones@email.com', '890-123-4567', 'Description of Ava')",
                "INSERT INTO Users
                VALUES ('robert_lee', 'Robert Lee', 'robert.lee@email.com', '901-234-5678', 'Description of Robert')",
                "INSERT INTO Users
                VALUES ('sophia_taylor', 'Sophia Taylor', 'sophia.taylor@email.com', '012-345-6789', 'Description of Sophia')",
                
                
                "INSERT INTO Companies
                VALUES (1, 'ABC Inc.', '123 Main Street, Vancouver')",
                "INSERT INTO Companies
                VALUES (2, 'XYZ Corp', '456 Elm Avenue, Toronto')",
                "INSERT INTO Companies
                VALUES (3, 'Tech Solutions Ltd.', '789 Oak Lane, Montreal')",
                "INSERT INTO Companies
                VALUES (4, 'Global Innovations', '101 Pine Road, Calgary')",
                "INSERT INTO Companies
                VALUES (5, 'Acme Industries', '222 Cedar Street, Edmonton')",
                
                "INSERT INTO Recruiters
                VALUES ('john_doe', 1)",
                "INSERT INTO Recruiters
                VALUES ('jane_smith', 2)",
                "INSERT INTO Recruiters
                VALUES ('michael_johnson', 3)",
                "INSERT INTO Recruiters
                VALUES ('emily_brown', 4)",
                "INSERT INTO Recruiters
                VALUES ('william_davis', 5)",
                
               "INSERT INTO JobSeekers
                VALUES ('olivia_wilson')",
                "INSERT INTO JobSeekers
                VALUES ('james_miller')",
                "INSERT INTO JobSeekers
                VALUES ('ava_jones')",
                "INSERT INTO JobSeekers
                VALUES ('robert_lee')",
                "INSERT INTO JobSeekers
                VALUES ('sophia_taylor')",
            
                "INSERT INTO JobPosts
                VALUES (1, 'john_doe', 'Software Engineer', 'Online', 80000, TO_DATE('2023-10-18', 'YYYY-MM-DD'), 'Full-time',
                        'We are looking for a software engineer with strong programming skills.', TO_DATE('2023-11-15', 'YYYY-MM-DD'),
                        'Bachelor''s degree in Computer Science, Proficiency in Java, 2+ years of experience', 50)",
                "INSERT INTO JobPosts
                VALUES (2, 'jane_smith', 'Marketing Manager', '456 Elm Avenue, Toronto', 70000, TO_DATE('2023-10-19', 'YYYY-MM-DD'), 'Full-time',
                        'We need an experienced marketing manager to lead our marketing team.', TO_DATE('2023-11-20', 'YYYY-MM-DD'),
                        'Bachelor''s degree in Marketing, 5+ years of marketing experience', 20)",
                "INSERT INTO JobPosts
                VALUES (3, 'michael_johnson', 'Data Analyst', '123 Main Street, Vancouver', 30000, TO_DATE('2023-10-19', 'YYYY-MM-DD'), 'Internship',
                        'We are hiring a data analyst intern for a short-term project.', TO_DATE('2023-11-10', 'YYYY-MM-DD'),
                        'Strong data analysis skills, familiarity with Python and database', 30)",
                "INSERT INTO JobPosts
                VALUES (4, 'emily_brown', 'Graphic Designer', 'Online', 55000, TO_DATE('2023-10-21', 'YYYY-MM-DD'), 'Full-time',
                        'Looking for a creative graphic designer to work on various design projects.',
                        TO_DATE('2023-11-25', 'YYYY-MM-DD'), 'Graphic design experience, proficiency in Adobe Creative Suite', 12)",
                "INSERT INTO JobPosts
                VALUES (5, 'william_davis', 'Customer Support Representative', '101 Pine Road, Calgary', 45000, TO_DATE('2023-10-22', 'YYYY-MM-DD'),
                        'Full-time', 'We are seeking a customer support representative to assist our customers.',
                        TO_DATE('2023-11-30', 'YYYY-MM-DD'), 'Excellent communication skills, customer service experience', 15)",
                
                
                "INSERT INTO Resumes
                VALUES ('http://example.com/resume1-olivia_wilson', 'olivia_wilson')",
                "INSERT INTO Resumes
                VALUES ('http://example.com/resume1-james_miller', 'james_miller')",
                "INSERT INTO Resumes
                VALUES ('http://example.com/resume1-ava_jones', 'ava_jones')",
                "INSERT INTO Resumes
                VALUES ('http://example.com/resume1-robert_lee', 'robert_lee')",
                "INSERT INTO Resumes
                VALUES ('http://example.com/resume1-sophia_taylor', 'sophia_taylor')",
                
                "INSERT INTO Applications
                VALUES (1, 'john_doe', 1, TO_DATE('2023-10-19', 'YYYY-MM-DD'), 'http://example.com/coverletter1-olivia_wilson', 'http://example.com/resume1-olivia_wilson',
                        'Under Review', TO_DATE('2023-10-20', 'YYYY-MM-DD'))",
                "INSERT INTO Applications
                VALUES (2, 'jane_smith', 2, TO_DATE('2023-10-20', 'YYYY-MM-DD'), NULL, 'http://example.com/resume1-james_miller', 'Interviewing',
                        TO_DATE('2023-10-20', 'YYYY-MM-DD'))",
                "INSERT INTO Applications
                VALUES (3, 'michael_johnson', 3, TO_DATE('2023-10-21', 'YYYY-MM-DD'), 'http://example.com/coverletter1-ava_jones', 'http://example.com/resume1-ava_jones',
                        'Interviewing', TO_DATE('2023-10-22', 'YYYY-MM-DD'))",
                "INSERT INTO Applications
                VALUES (4, 'emily_brown', 4, TO_DATE('2023-10-30', 'YYYY-MM-DD'), 'http://example.com/coverletter1-robert_lee', 'http://example.com/resume1-robert_lee',
                        'Accepted', TO_DATE('2023-10-30', 'YYYY-MM-DD'))",
                "INSERT INTO Applications
                VALUES (5, NULL, NULL, TO_DATE('2023-10-10', 'YYYY-MM-DD'), 'http://example.com/coverletter1-sophia_taylor',
                        'http://example.com/resume1-sophia_taylor', 'Incomplete application', NULL)",
                
                "INSERT INTO ScheduledInterviews
                VALUES (1, 1, '123 Main St, City1', 'In-Person', TO_DATE('2023-10-28T10:00', 'YYYY-MM-DD\"T\"HH24:MI'), 'PST')",
                "INSERT INTO ScheduledInterviews
                VALUES (2, 2, '143 Main St, City2', 'In-Person', TO_DATE('2023-10-25T11:00', 'YYYY-MM-DD\"T\"HH24:MI'), 'EST')",
                "INSERT INTO ScheduledInterviews
                VALUES (3, 3, 'https://zoom.us/j/123', 'Online', TO_DATE('2023-10-15T13:00', 'YYYY-MM-DD\"T\"HH24:MI'), 'ADT')",
                "INSERT INTO ScheduledInterviews
                VALUES (4, 4, '193 University St, City4', 'In-Person', TO_DATE('2023-10-25T10:00', 'YYYY-MM-DD\"T\"HH24:MI'), 'CDT')",
                "INSERT INTO ScheduledInterviews
                VALUES (5, 5, 'https://zoom.us/j/456', 'Online', TO_DATE('2023-10-10T14:30', 'YYYY-MM-DD\"T\"HH24:MI'), 'MST')",
                
                "INSERT INTO Applications_ScheduledInterviews
                VALUES (1, 1)",
                "INSERT INTO Applications_ScheduledInterviews
                VALUES (2, 2)",
                "INSERT INTO Applications_ScheduledInterviews
                VALUES (3, 3)",
                "INSERT INTO Applications_ScheduledInterviews
                VALUES (4, 4)",
                "INSERT INTO Applications_ScheduledInterviews
                VALUES (5, 5)",
                
                "INSERT INTO Interviewers_Attend
                VALUES (1, 1, 'Anna', '111-111-2222')",
                "INSERT INTO Interviewers_Attend
                VALUES (2, 2, 'Jone', '222-222-3333')",
                "INSERT INTO Interviewers_Attend
                VALUES (3, 3, 'Sandrew', '333-333-4444')",
                "INSERT INTO Interviewers_Attend
                VALUES (4, 4, 'Peter', '444-444-5555')",
                "INSERT INTO Interviewers_Attend
                VALUES (5, 5, 'Jack', '555-555-6666')",
                
                "INSERT INTO LocationDetails
                VALUES ('V6T1Z1', 'Vancouver', 'BC')",
                "INSERT INTO LocationDetails
                VALUES ('M5R0A3', 'Toronto', 'ON')",
                "INSERT INTO LocationDetails
                VALUES ('T6G2R3', 'Edmonton', 'AB')",
                "INSERT INTO LocationDetails
                VALUES ('V6T1Z4', 'Vancouver', 'BC')",
                "INSERT INTO LocationDetails
                VALUES ('V6T1Z2', 'Vancouver', 'BC')",
                
                "INSERT INTO CareerFairs
                VALUES (1, 'BusinessCareerFair2023', 'V6T1Z1', 'University of British Columbia', TO_DATE('2023-10-10', 'YYYY-MM-DD'))",
                "INSERT INTO CareerFairs
                VALUES (2, 'TechnicalCareerFair2024', 'M5R0A3', 'University of Toronto', TO_DATE('2024-01-10', 'YYYY-MM-DD'))",
                "INSERT INTO CareerFairs
                VALUES (3, 'EngineerCareerFair2024', 'T6G2R3', 'University of Alberta', TO_DATE('2024-09-10', 'YYYY-MM-DD'))",
                "INSERT INTO CareerFairs
                VALUES (4, 'TechnicalCareerFair2023', 'V6T1Z4', 'University of British Columbia', TO_DATE('2023-05-10', 'YYYY-MM-DD'))",
                "INSERT INTO CareerFairs
                VALUES (5, 'EngineerCareerFair2023', 'V6T1Z2', 'University of British Columbia', TO_DATE('2023-05-10', 'YYYY-MM-DD'))",
                
                "INSERT INTO Companies_CareerFairs
                VALUES (1, 1)",
                "INSERT INTO Companies_CareerFairs
                VALUES (2, 2)",
                "INSERT INTO Companies_CareerFairs
                VALUES (3, 3)",
                "INSERT INTO Companies_CareerFairs
                VALUES (4, 4)",
                "INSERT INTO Companies_CareerFairs
                VALUES (5, 5)",
                
                "INSERT INTO JobSeekers_CareerFairs
                VALUES ('olivia_wilson', 1)",
                "INSERT INTO JobSeekers_CareerFairs
                VALUES ('james_miller', 2)",
                "INSERT INTO JobSeekers_CareerFairs
                VALUES ('ava_jones', 3)",
                "INSERT INTO JobSeekers_CareerFairs
                VALUES ('robert_lee', 4)",
                "INSERT INTO JobSeekers_CareerFairs
                VALUES ('sophia_taylor', 5)"
                
            ];
            
            
            foreach ($sqlQueries as $sqlQuery) {
                executePlainSQL($sqlQuery);
                OCICommit($db_conn);
            }
            if ($success == True) {
                echo ("<p style='color: blue;'>Successfully resetted.</p>");
            }
        }

		if (isset($_POST['reset'])) {
            handlePOSTRequest();
        }
        ?>

        <hr />

        <h2>View All Table</h2>
        <a href="job_portal_view.php"><button>View All Table</button></a>

        <hr />

        <h2>Find User(s)</h2>
        <a href="find_user.php"><button>Find User(s)</button></a>
        <hr />


        <h2>User Sign-up</h2>
        <form method="POST" action="job_portal.php">
            <input type="hidden" id="insertUserQueryRequest" name="insertUserQueryRequest">
            
            Username* <input type="text" name="username" required="required"> <br><br>
            Password* <input type="password" name="password" required="required"> <br><br>

            <label for="userTypeSelect">User Type* </label>
            <select name="userType" id="userTypeSelect" required="required">
                <option disabled selected value> -- select an option -- </option>
                <option value="recruiter" name="recruiter">Recruiter</option>
                <option value="jobseeker" name="jobseeker">Job Seeker</option>
            </select>
            <br><br>
            
            Name*  <input type="text" name="name" required="required"> <br><br>
            Email Address* <input type="email" name="email" required="required"> <br><br>
            Phone Number (Eg. 123-456-7890) <input type="text" name="phone"> <br><br>
            Description <input type="text" name="description"> <br><br>

            <div id="companyInfo" style="display: none;">
                <label for="companyOption">Company* </label>
                <select name="companyOption" id="companyOption">
                    <option disabled selected value> -- select an option -- </option>
                    <option value="existing" name="existing">Use Existing Company ID</option>
                    <option value="createNew" name="createNew">Create New Company</option>
                </select>
                <br><br>
                <div id="existingCompany" style="display: none;">
                    Company ID* <input type="text" name="companyID"> <br><br>
                </div>
                <div id="newCompany" style="display: none;">
                    New Company Info:<br />
                    Company Name* <input type="text" name="companyName"> <br><br>
                    Company Address <input type="text" name="companyAddress"> <br><br>
                </div>
            </div>

            <input type="submit" value="Sign Up" name="insertSubmit">
        </form>

        <script>
            const userTypeSelect = document.getElementById('userTypeSelect');
            const companyInfoDiv = document.getElementById('companyInfo');
            const existingCompanyDiv = document.getElementById('existingCompany');
            const newCompanyDiv = document.getElementById('newCompany');
            const companyOption = document.getElementById('companyOption');

            userTypeSelect.addEventListener('change', function () {
                if (userTypeSelect.value === 'recruiter') {
                    companyInfoDiv.style.display = 'block';
                    companyOption.required = true;
                } else {
                    companyInfoDiv.style.display = 'none';
                    companyOption.required = false;
                }
            });

            companyOption.addEventListener('change', function () {
                if (companyOption.value === 'existing') {
                    existingCompanyDiv.style.display = 'block';
                    newCompanyDiv.style.display = 'none';
                    document.getElementById('companyID').required = true;
                    document.getElementById('companyName').required = false;
                } else if (companyOption.value === 'createNew') {
                    existingCompanyDiv.style.display = 'none';
                    newCompanyDiv.style.display = 'block';
                    document.getElementById('companyID').required = false;
                    document.getElementById('companyName').required = true;
                }
            });
        </script>

        <?php
        function handleInsertUserRequest() {
            global $db_conn, $success;

            if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
                echo "<p style='color: red;'>Invalid username, only alphanumeric characters and underscores are allowed, please try again</p>";
                return;
            }
            if (!preg_match('/^[a-zA-Z\s]+$/', $_POST['name'])) {
                echo "<p style='color: red;'>Invalid format for name, please try again.</p>";
                return;
            }
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                echo "<p style='color: red;'>Invalid email, please try again.</p>";
                return;
            }
            if (!empty($_POST['phone']) && !preg_match('/^\d{3}-\d{3}-\d{4}$/', $_POST['phone'])) {
                echo "<p style='color: red;'>Invalid format for phone number, please try again.</p>";
                return;
            }

            //login info insert
            $logintuple = array (
                ":bind1" => htmlspecialchars($_POST['username']),

                ":bind2" => password_hash($_POST['password'], PASSWORD_DEFAULT)
            );

            $loginAlltuples = array ($logintuple);
            executeBoundSQL("insert into UserLogInfo values (:bind1, :bind2)", $loginAlltuples);
            OCICommit($db_conn);

            if (!$success) {
                echo ("<p style='color: red;'>Sign up failed: Username already exists.</p>");
                return;
            }
            
            //user insert
            $userTuple = array(
                ":bind1" => htmlspecialchars($_POST['username']),
                ":bind2" => htmlspecialchars($_POST['name']),
                ":bind3" => htmlspecialchars($_POST['email']),
                ":bind4" => htmlspecialchars($_POST['phone']),
                ":bind5" => htmlspecialchars($_POST['description']  , ENT_QUOTES, 'UTF-8')
            );

            $userAlltuples = array($userTuple);
            executeBoundSQL("insert into Users values (:bind1, :bind2, :bind3, :bind4, :bind5)", $userAlltuples);
            OCICommit($db_conn);

            if (!$success) {
                echo ("<p style='color: red;'>Sign up failed: Email already exists.</p>");
                executeBoundSQL("delete from UserLogInfo where UserName = (:bind1)", $loginAlltuples);
                OCICommit($db_conn);
                return;
            }

            if ($_POST['userType'] == "jobseeker") {
                executeBoundSQL("insert into JobSeekers values (:bind1)", $userAlltuples);
                OCICommit($db_conn);
                echo ("<p style='color: green;'>Successfully signed up.</p>");
            } else {
                if ($_POST['companyOption'] == "createNew") {
                    $companyTuple = array(
                        ":bind1" => htmlspecialchars($_POST['companyName']),
                        ":bind2" => htmlspecialchars($_POST['companyAddress'])
                    );
        
                    $companyAlltuples = array($companyTuple);
                    executeBoundSQL("insert into Companies values (CompanyId_Sequence.nextval, :bind1, :bind2)", $companyAlltuples);
                    OCICommit($db_conn);
                    $companyId = executePlainSQL("SELECT CompanyId_Sequence.currval FROM dual");
                    $id = oci_fetch_assoc($companyId)['CURRVAL'];
                    echo "<br> The company id is: " . $id . "<br>";
                }
                if ($success) {
                    if ($_POST['companyOption'] == "existing") {
                        $id = $_POST['companyID'];
                    }
                    //user insert
                    $recruiterTuple = array(
                        ":bind1" => $_POST['username'],
                        ":bind2" => $id
                    );
        
                    $recruiterAlltuples = array($recruiterTuple);
                    executeBoundSQL("insert into Recruiters values (:bind1, :bind2)", $recruiterAlltuples);
                    OCICommit($db_conn);

                    if ($success == FALSE) {
                        echo ("<p style='color: red;'>Sign up failed: Invalid company ID.</p>");
                        executeBoundSQL("delete from UserLogInfo where UserName = (:bind1)", $loginAlltuples);
                        executeBoundSQL("delete from Users where UserName = (:bind1)", $userAlltuples);
                        executeBoundSQL("delete from Companies where CompanyId = (:bind2)", $recruiterAlltuples);
                        OCICommit($db_conn);
                    } else {
                        echo ("<p style='color: green;'>Successfully signed up.</p>");
                    }
                } else {
                    echo ("<p style='color: red;'>Sign up failed: Company already exists</p>");
                    executeBoundSQL("delete from UserLogInfo where UserName = (:bind1)", $loginAlltuples);
                    executeBoundSQL("delete from Users where UserName = (:bind1)", $userAlltuples);
                    OCICommit($db_conn);
                }
            }
        }

        if (isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        }
        ?>

        <hr />

        <h2>User Log-in</h2>
        <form method="POST" action="login_validate.php">
            <input type="hidden" id="loginQueryRequest" name="loginQueryRequest">
            
            Username: <input type="text" name="username" required="required"> <br><br>
            Password: <input type="password" name="password" required="required"> <br><br>

            <input type="submit" value="Log In" name="loginSubmit">
        </form>

        <?php
        echo "<p style='color: red;'>" . $_SESSION["error_message"] . "</p>";
        session_unset(); 
        ?>

        <hr />

        <h2>Count Users</h2>
        <form method="GET" action="job_portal.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countUserLogInfoTupleRequest" name="countUserLogInfoTupleRequest">
            <input type="submit" name="countTuples1"></p>
        </form>

        <h2>Count Recruiters</h2>
        <form method="GET" action="job_portal.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countRecruitersTupleRequest" name="countRecruitersTupleRequest">
            <input type="submit" name="countTuples2"></p>
        </form>

        <h2>Count Job Seekers</h2>
        <form method="GET" action="job_portal.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countJobSeekersTupleRequest" name="countJobSeekersTupleRequest">
            <input type="submit" name="countTuples3"></p>
        </form>

        <h2>Count Companies</h2>
        <form method="GET" action="job_portal.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countCompaniesTupleRequest" name="countCompaniesTupleRequest">
            <input type="submit" name="countTuples4"></p>
        </form>


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

        function handleCountRequest1() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM UserLogInfo");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in UserLogInfo: " . $row[0] . "<br>";
            }
        }
        function handleCountRequest2() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM Companies");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Companies: " . $row[0] . "<br>";
            }
        }
        function handleCountRequest3() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM Recruiters");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Recruiters: " . $row[0] . "<br>";
            }
        }

        function handleCountRequest4() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM JobSeekers");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Job Seeker: " . $row[0] . "<br>";
            }
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('insertUserQueryRequest', $_POST)) {
                    handleInsertUserRequest();
                }  else if (array_key_exists('loginQueryRequest', $_POST)) {
                    handleLoginRequest();
                }

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countUserLogInfoTupleRequest', $_GET)) {
                    handleCountRequest1();
                } else if (array_key_exists('countCompaniesTupleRequest', $_GET)) {
                    handleCountRequest2();
                } else if (array_key_exists('countRecruitersTupleRequest', $_GET)) {
                    handleCountRequest3();
                } else if (array_key_exists('countJobSeekersTupleRequest', $_GET)) {
                    handleCountRequest4();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['loginSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTuples1']) || isset($_GET['countTuples2']) || isset($_GET['countTuples3']) || isset($_GET['countTuples4'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>