DROP TABLE JOBSEEKERS_CAREERFAIRS;
DROP TABLE COMPANIES_CAREERFAIRS;
DROP TABLE CAREERFAIRS;
DROP TABLE LOCATIONDETAILS;
DROP TABLE INTERVIEWERS_ATTEND;
DROP TABLE APPLICATIONS_SCHEDULEDINTERVIEWS;
DROP TABLE SCHEDULEDINTERVIEWS;
DROP TABLE APPLICATIONS;
DROP TABLE RESUMES;
DROP TABLE JOBPOSTS;
DROP TABLE JOBSEEKERS;
DROP TABLE RECRUITERS;
DROP TABLE COMPANIES;
DROP TABLE USERS;
DROP TABLE USERLOGINFO;

CREATE TABLE UserLogInfo (
  UserName VARCHAR(100) PRIMARY KEY,
  PassWord VARCHAR(100) NOT NULL
);

CREATE TABLE Users (
  UserId INTEGER PRIMARY KEY,
  Name VARCHAR(100) NOT NULL,
  EmailAddress VARCHAR(100) NOT NULL UNIQUE,
  PhoneNumber VARCHAR(20) UNIQUE,
  Description VARCHAR(4000),
  UserName VARCHAR (100) NOT NULL UNIQUE,
  FOREIGN KEY (UserName) REFERENCES UserLogInfo ON DELETE CASCADE
);

CREATE TABLE Companies (
  CompanyId INTEGER PRIMARY KEY,
  CompanyName VARCHAR(100) NOT NULL,
  Address VARCHAR(100),
  UNIQUE (CompanyName, Address)
);

CREATE TABLE Recruiters (
  UserId INTEGER PRIMARY KEY,
  CompanyId INTEGER,
  FOREIGN KEY (UserId) REFERENCES Users ON DELETE CASCADE,
  FOREIGN KEY (CompanyId) REFERENCES Companies ON DELETE CASCADE
);

CREATE TABLE JobSeekers (
  UserId INTEGER PRIMARY KEY,
  FOREIGN KEY (UserId) REFERENCES Users ON DELETE CASCADE
);

CREATE TABLE JobPosts (
  JobPostId INTEGER PRIMARY KEY,
  RecruiterId INTEGER,
  Title VARCHAR(100) NOT NULL,
  Location VARCHAR(100),
  Salary INTEGER,
  PostDate DATE NOT NULL,
  JobType VARCHAR(100) NOT NULL,
  Description VARCHAR(4000) NOT NULL,
  Deadline DATE Not NULL,
  Requirements VARCHAR(4000),
  NumOfApplications INTEGER NOT NULL,
  FOREIGN KEY (RecruiterId) REFERENCES Recruiters ON DELETE CASCADE
);


CREATE TABLE Resumes (
    Resume VARCHAR(4000) PRIMARY KEY,
    JobSeekerId INTEGER NOT NULL,
    FOREIGN KEY (JobSeekerId) REFERENCES JobSeekers(UserId)
);

CREATE TABLE Applications (
    ApplicationId INTEGER PRIMARY KEY,
    RecruiterId INTEGER,
    JobPostId INTEGER,
    CreateDate DATE NOT NULL,
    CoverLetter VARCHAR(4000),
    Resume VARCHAR(4000) NOT NULL,
    Status VARCHAR(100) NOT NULL,
    ApplyDate DATE,
    FOREIGN KEY (RecruiterId) REFERENCES Recruiters(UserId),
    FOREIGN KEY (JobPostId) REFERENCES JobPosts(JobPostId),
    FOREIGN KEY (Resume) REFERENCES Resumes(Resume)
);

CREATE TABLE ScheduledInterviews (
    InterviewId INTEGER PRIMARY KEY,
    JobPostId INTEGER NOT NULL,
    Location VARCHAR(255) NOT NULL,
    InterviewMode CHAR(10) NOT NULL,
    DateTime DATE NOT NULL,
    TimeZone VARCHAR(10) NOT NULL,
    FOREIGN KEY (JobPostId) REFERENCES JobPosts(JobPostId)
);


CREATE TABLE Applications_ScheduledInterviews (
	InterviewId INTEGER,
	ApplicationId INTEGER,
	PRIMARY KEY (InterviewId, ApplicationId),
	FOREIGN KEY (InterviewId) REFERENCES ScheduledInterviews ON DELETE CASCADE,
	FOREIGN KEY (ApplicationId) REFERENCES Applications ON DELETE CASCADE
);

CREATE TABLE Interviewers_Attend(
	InterviewerId INTEGER,
	InterviewId INTEGER,
	Name VARCHAR(100) NOT NULL,
	ContactNum VARCHAR(100),
PRIMARY KEY (InterviewerId, InterviewId),
FOREIGN KEY (InterviewId) REFERENCES ScheduledInterviews ON DELETE CASCADE
);

CREATE TABLE LocationDetails(
	PostalCode VARCHAR(10) PRIMARY KEY,
	City VARCHAR(100) NOT NULL,
	Province VARCHAR(100) NOT NULL
);

CREATE TABLE CareerFairs (
	EventId INTEGER PRIMARY KEY,
	EventName VARCHAR(100) NOT NULL,
	PostalCode VARCHAR(10) NOT NULL,
	Location VARCHAR(500) NOT NULL,
	EventDate DATE NOT NULL,
FOREIGN KEY (PostalCode) REFERENCES LocationDetails ON DELETE CASCADE
);

CREATE TABLE Companies_CareerFairs(
	CompanyId INTEGER,
	EventId INTEGER,
	PRIMARY KEY (CompanyId, EventId),
	FOREIGN KEY (CompanyId) REFERENCES Companies ON DELETE CASCADE,
FOREIGN KEY (EventId) REFERENCES CareerFairs ON DELETE CASCADE
);

CREATE TABLE JobSeekers_CareerFairs(
	JobSeekerId INTEGER,
	EventId INTEGER,
	PRIMARY KEY (JobSeekerId, EventId),
	FOREIGN KEY (JobSeekerId) REFERENCES JobSeekers ON DELETE CASCADE,
FOREIGN KEY (EventId) REFERENCES CareerFairs ON DELETE CASCADE
);

INSERT INTO UserLogInfo
VALUES ('john_doe', 'johnpassword123');
INSERT INTO UserLogInfo
VALUES ('jane_smith', 'janepassword456!');
INSERT INTO UserLogInfo
VALUES ('michael_johnson', 'michaelpassword789');
INSERT INTO UserLogInfo
VALUES ('emily_brown', 'emilypassword123');
INSERT INTO UserLogInfo
VALUES ('william_davis', 'williampassword456');
INSERT INTO UserLogInfo
VALUES ('olivia_wilson', 'oliviapassword789');
INSERT INTO UserLogInfo
VALUES ('james_miller', 'jamespassword123');
INSERT INTO UserLogInfo
VALUES ('ava_jones', 'avapassword456');
INSERT INTO UserLogInfo
VALUES ('robert_lee', 'robertpassword789');
INSERT INTO UserLogInfo
VALUES ('sophia_taylor', 'sophiapassword123');

INSERT INTO Users
VALUES (1, 'John Doe', 'john.doe@email.com', '123-456-7890', 'Description of John', 'john_doe');
INSERT INTO Users
VALUES (2, 'Jane Smith', 'jane.smith@email.com', '234-567-8901', 'Description of Jane', 'jane_smith');
INSERT INTO Users
VALUES (3, 'Michael Johnson', 'michael.johnson@email.com', '345-678-9012', 'Description of Michael', 'michael_johnson');
INSERT INTO Users
VALUES (4, 'Emily Brown', 'emily.brown@email.com', '456-789-0123', 'Description of Emily', 'emily_brown');
INSERT INTO Users
VALUES (5, 'William Davis', 'william.davis@email.com', '567-890-1234', 'Description of William', 'william_davis');
INSERT INTO Users
VALUES (6, 'Olivia Wilson', 'olivia.wilson@email.com', '678-901-2345', 'Description of Olivia', 'olivia_wilson');
INSERT INTO Users
VALUES (7, 'James Miller', 'james.miller@email.com', '789-012-3456', 'Description of James', 'james_miller');
INSERT INTO Users
VALUES (8, 'Ava Jones', 'ava.jones@email.com', '890-123-4567', 'Description of Ava', 'ava_jones');
INSERT INTO Users
VALUES (9, 'Robert Lee', 'robert.lee@email.com', '901-234-5678', 'Description of Robert', 'robert_lee');
INSERT INTO Users
VALUES (10, 'Sophia Taylor', 'sophia.taylor@email.com', '012-345-6789', 'Description of Sophia', 'sophia_taylor');

INSERT INTO Companies
VALUES (1, 'ABC Inc.', '123 Main Street, Vancouver');
INSERT INTO Companies
VALUES (2, 'XYZ Corp', '456 Elm Avenue, Toronto');
INSERT INTO Companies
VALUES (3, 'Tech Solutions Ltd.', '789 Oak Lane, Montreal');
INSERT INTO Companies
VALUES (4, 'Global Innovations', '101 Pine Road, Calgary');
INSERT INTO Companies
VALUES (5, 'Acme Industries', '222 Cedar Street, Edmonton');

INSERT INTO Recruiters
VALUES (1, 1);
INSERT INTO Recruiters
VALUES (2, 2);
INSERT INTO Recruiters
VALUES (3, 3);
INSERT INTO Recruiters
VALUES (4, 4);
INSERT INTO Recruiters
VALUES (5, 5);

INSERT INTO JobSeekers
VALUES (6);
INSERT INTO JobSeekers
VALUES (7);
INSERT INTO JobSeekers
VALUES (8);
INSERT INTO JobSeekers
VALUES (9);
INSERT INTO JobSeekers
VALUES (10);

INSERT INTO JobPosts
VALUES (1, 1, 'Software Engineer', 'Online', 80000, TO_DATE('2023-10-18','YYYY-MM-DD'), 'Full-time', 'We are looking for a software engineer with strong programming skills.', TO_DATE('2023-11-15','YYYY-MM-DD'), 'Bachelor''s degree in Computer Science, Proficiency in Java, 2+ years of experience', 50);
INSERT INTO JobPosts
VALUES (2, 2, 'Marketing Manager', '456 Elm Avenue, Toronto', 70000, TO_DATE('2023-10-19','YYYY-MM-DD'), 'Full-time', 'We need an experienced marketing manager to lead our marketing team.', TO_DATE('2023-11-20','YYYY-MM-DD'), 'Bachelor''s degree in Marketing, 5+ years of marketing experience', 20);
INSERT INTO JobPosts
VALUES (3, 1, 'Data Analyst', '123 Main Street, Vancouver', 30000, TO_DATE('2023-10-19','YYYY-MM-DD'), 'Internship', 'We are hiring a data analyst intern for a short-term project.', TO_DATE('2023-11-10','YYYY-MM-DD'), 'Strong data analysis skills, familiarity with Python and database', 30);
INSERT INTO JobPosts
VALUES (4, 3, 'Graphic Designer', 'Online', 55000, TO_DATE('2023-10-21','YYYY-MM-DD'), 'Full-time', 'Looking for a creative graphic designer to work on various design projects.', TO_DATE('2023-11-25','YYYY-MM-DD'), 'Graphic design experience, proficiency in Adobe Creative Suite', 12);
INSERT INTO JobPosts
VALUES (5, 4, 'Customer Support Representative', '101 Pine Road, Calgary', 45000, TO_DATE('2023-10-22','YYYY-MM-DD'), 'Full-time', 'We are seeking a customer support representative to assist our customers.', TO_DATE('2023-11-30','YYYY-MM-DD'), 'Excellent communication skills, customer service experience', 15);


INSERT INTO Resumes
VALUES ('http://example.com/resume1', 6);
INSERT INTO Resumes
VALUES ('http://example.com/resume2', 7);
INSERT INTO Resumes
VALUES ('http://example.com/resume3', 8);
INSERT INTO Resumes
VALUES ('http://example.com/resume4', 9);
INSERT INTO Resumes
VALUES ('http://example.com/resume5', 10);

INSERT INTO Applications
VALUES (1, 1, 1, TO_DATE('2023-10-19','YYYY-MM-DD'), 'http://example.com/coverletter1', 'http://example.com/resume1', 'Under Review', TO_DATE('2023-10-20','YYYY-MM-DD'));
INSERT INTO Applications
VALUES (2, 1, 2, TO_DATE('2023-10-20','YYYY-MM-DD'), NULL, 'http://example.com/resume2', 'Interviewing', TO_DATE('2023-10-20','YYYY-MM-DD'));
INSERT INTO Applications
VALUES (3, 2, 3, TO_DATE('2023-10-21','YYYY-MM-DD'), 'http://example.com/coverletter3', 'http://example.com/resume3', 'Interviewing', TO_DATE('2023-10-22','YYYY-MM-DD'));
INSERT INTO Applications
VALUES (4, 3, 4, TO_DATE('2023-10-30','YYYY-MM-DD'), 'http://example.com/coverletter4', 'http://example.com/resume4', 'Position closed', TO_DATE('2023-10-30','YYYY-MM-DD'));
INSERT INTO Applications
VALUES (5, 4, NULL, TO_DATE('2023-10-10','YYYY-MM-DD'), 'http://example.com/coverletter5', 'http://example.com/resume5', 'Incomplete application', NULL);

INSERT INTO ScheduledInterviews
VALUES (1, 1, '123 Main St, City1', 'In-Person', TO_DATE('2023-10-28 10:00:00', 'YYYY-MM-DD hh24:mi:ss'), 'PST');
INSERT INTO ScheduledInterviews
VALUES (2, 2, '143 Main St, City2', 'In-Person', TO_DATE('2023-10-25 11:00:00', 'YYYY-MM-DD hh24:mi:ss'), 'EST');
INSERT INTO ScheduledInterviews
VALUES (3, 3, 'https://zoom.us/j/123', 'Online', TO_DATE('2023-10-15 13:00:00', 'YYYY-MM-DD hh24:mi:ss'), 'ADT');
INSERT INTO ScheduledInterviews
VALUES (4, 4, '193 University St, City4', 'In-Person', TO_DATE('2023-10-25 10:00:00', 'YYYY-MM-DD hh24:mi:ss'), 'CDT');
INSERT INTO ScheduledInterviews
VALUES (5, 5, 'https://zoom.us/j/456', 'Online', TO_DATE('2023-10-10 14:30:00', 'YYYY-MM-DD hh24:mi:ss'), 'MST');

INSERT INTO Applications_ScheduledInterviews
VALUES (1, 1);
INSERT INTO Applications_ScheduledInterviews
VALUES (2, 2);
INSERT INTO Applications_ScheduledInterviews
VALUES (3, 3);
INSERT INTO Applications_ScheduledInterviews
VALUES (4, 4);
INSERT INTO Applications_ScheduledInterviews
VALUES (5, 5);

INSERT INTO Interviewers_Attend
VALUES (01, 1, 'Anna', 11111111);
INSERT INTO Interviewers_Attend
VALUES (02, 2, 'Jone', 22222222);
INSERT INTO Interviewers_Attend
VALUES (03, 3, 'Sandrew', 33333333);
INSERT INTO Interviewers_Attend
VALUES (04, 4, 'Peter', 44444444);
INSERT INTO Interviewers_Attend
VALUES (05, 5, 'Jack', 55555555);

INSERT INTO LocationDetails
VALUES ('V6T1Z1', 'Vancouver', 'BC');
INSERT INTO LocationDetails
VALUES ('M5R0A3', 'Toronto', 'ON');
INSERT INTO LocationDetails
VALUES ('T6G2R3', 'Edmonton', 'AB');
INSERT INTO LocationDetails
VALUES ('V6T1Z4', 'Vancouver', 'BC');
INSERT INTO LocationDetails
VALUES ('V6T1Z2', 'Vancouver', 'BC');

INSERT INTO CareerFairs
VALUES (91, 'BusinessCareerFair2023', 'V6T1Z1', 'University of British Columbia', TO_DATE('2023-10-10','YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (92, 'TechnicalCareerFair2024', 'M5R0A3', 'University of Toronto', TO_DATE('2024-01-10','YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (93, 'EngineerCareerFair2024', 'T6G2R3', 'University of Alberta', TO_DATE('2024-09-10','YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (94, 'TechnicalCareerFair2023', 'V6T1Z4', 'University of British Columbia', TO_DATE('2023-05-10','YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (95, 'EngineerCareerFair2023', 'V6T1Z2', 'University of British Columbia',TO_DATE('2023-05-10','YYYY-MM-DD'));

INSERT INTO Companies_CareerFairs
VALUES (1, 91);
INSERT INTO Companies_CareerFairs
VALUES (2, 92);
INSERT INTO Companies_CareerFairs
VALUES (3, 93);
INSERT INTO Companies_CareerFairs
VALUES (4, 94);
INSERT INTO Companies_CareerFairs
VALUES (5, 95);

INSERT INTO JobSeekers_CareerFairs
VALUES (6, 91);
INSERT INTO JobSeekers_CareerFairs
VALUES (7, 92);
INSERT INTO JobSeekers_CareerFairs
VALUES (8, 93);
INSERT INTO JobSeekers_CareerFairs
VALUES (9, 94);
INSERT INTO JobSeekers_CareerFairs
VALUES (10, 95);
