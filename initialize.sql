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
DROP SEQUENCE CompanyId_Sequence;
DROP SEQUENCE JobPostId_Sequence;
DROP SEQUENCE ApplicationId_Sequence;
DROP SEQUENCE InterviewId_Sequence;
DROP SEQUENCE InterviewerId_Sequence;
DROP SEQUENCE EventId_Sequence;

CREATE TABLE UserLogInfo
(
    UserName VARCHAR(100) PRIMARY KEY,
    PassWord VARCHAR(100) NOT NULL
);

CREATE TABLE Users
(
    UserName     VARCHAR(100) PRIMARY KEY,
    Name         VARCHAR(100) NOT NULL,
    EmailAddress VARCHAR(100) NOT NULL UNIQUE,
    PhoneNumber  VARCHAR(20) UNIQUE,
    Description  VARCHAR(4000),
    FOREIGN KEY (UserName) REFERENCES UserLogInfo ON DELETE CASCADE
);

CREATE SEQUENCE CompanyId_Sequence
    START WITH 1
    INCREMENT BY 1;

CREATE TABLE Companies
(
    CompanyId   INTEGER PRIMARY KEY,
    CompanyName VARCHAR(100) NOT NULL,
    Address     VARCHAR(100),
    UNIQUE (CompanyName, Address)
);

CREATE TABLE Recruiters
(
    UserName  VARCHAR(100) PRIMARY KEY,
    CompanyId INTEGER,
    FOREIGN KEY (UserName) REFERENCES Users ON DELETE CASCADE,
    FOREIGN KEY (CompanyId) REFERENCES Companies ON DELETE CASCADE
);

CREATE TABLE JobSeekers
(
    UserName VARCHAR(100) PRIMARY KEY,
    FOREIGN KEY (UserName) REFERENCES Users ON DELETE CASCADE
);


CREATE SEQUENCE JobPostId_Sequence
    START WITH 1
    INCREMENT BY 1;

CREATE TABLE JobPosts
(
    JobPostId         INTEGER PRIMARY KEY,
    RecruiterId       VARCHAR(100),
    Title             VARCHAR(100)  NOT NULL,
    Location          VARCHAR(100),
    Salary            INTEGER,
    PostDate          DATE          NOT NULL,
    JobType           VARCHAR(100)  NOT NULL,
    Description       VARCHAR(4000) NOT NULL,
    Deadline          DATE          Not NULL,
    Requirements      VARCHAR(4000),
    NumOfApplications INTEGER       NOT NULL,
    FOREIGN KEY (RecruiterId) REFERENCES Recruiters (UserName) ON DELETE CASCADE
);


CREATE TABLE Resumes
(
    Resume      VARCHAR(4000) PRIMARY KEY,
    JobSeekerId VARCHAR(100) NOT NULL,
    FOREIGN KEY (JobSeekerId) REFERENCES JobSeekers (UserName)
);

CREATE SEQUENCE ApplicationId_Sequence
    START WITH 1
    INCREMENT BY 1;

CREATE TABLE Applications
(
    ApplicationId INTEGER PRIMARY KEY,
    RecruiterId   VARCHAR(100),
    JobPostId     INTEGER,
    CreateDate    DATE          NOT NULL,
    CoverLetter   VARCHAR(4000),
    Resume        VARCHAR(4000) NOT NULL,
    Status        VARCHAR(100)  NOT NULL,
    ApplyDate     DATE,
    FOREIGN KEY (RecruiterId) REFERENCES Recruiters (UserName),
    FOREIGN KEY (JobPostId) REFERENCES JobPosts (JobPostId) ON DELETE SET NULL,
    FOREIGN KEY (Resume) REFERENCES Resumes (Resume)
);

CREATE SEQUENCE InterviewId_Sequence
    START WITH 1
    INCREMENT BY 1;

CREATE TABLE ScheduledInterviews
(
    InterviewId   INTEGER PRIMARY KEY,
    JobPostId     INTEGER,
    Location      VARCHAR(255) NOT NULL,
    InterviewMode CHAR(10)     NOT NULL,
    DateTime      DATE         NOT NULL,
    TimeZone      VARCHAR(10)  NOT NULL,
    FOREIGN KEY (JobPostId) REFERENCES JobPosts (JobPostId) ON DELETE SET NULL
);



CREATE TABLE Applications_ScheduledInterviews
(
    InterviewId   INTEGER,
    ApplicationId INTEGER,
    PRIMARY KEY (InterviewId, ApplicationId),
    FOREIGN KEY (InterviewId) REFERENCES ScheduledInterviews ON DELETE CASCADE,
    FOREIGN KEY (ApplicationId) REFERENCES Applications ON DELETE CASCADE
);

CREATE SEQUENCE InterviewerId_Sequence
    START WITH 1
    INCREMENT BY 1;

CREATE TABLE Interviewers_Attend
(
    InterviewerId INTEGER,
    InterviewId   INTEGER,
    Name          VARCHAR(100) NOT NULL,
    ContactNum    VARCHAR(100),
    PRIMARY KEY (InterviewerId, InterviewId),
    FOREIGN KEY (InterviewId) REFERENCES ScheduledInterviews ON DELETE CASCADE
);

CREATE TABLE LocationDetails
(
    PostalCode VARCHAR(10) PRIMARY KEY,
    City       VARCHAR(100) NOT NULL,
    Province   VARCHAR(100) NOT NULL
);

CREATE SEQUENCE EventId_Sequence
    START WITH 1
    INCREMENT BY 1;

CREATE TABLE CareerFairs
(
    EventId    INTEGER PRIMARY KEY,
    EventName  VARCHAR(100) NOT NULL,
    PostalCode VARCHAR(10)  NOT NULL,
    Location   VARCHAR(500) NOT NULL,
    EventDate  DATE         NOT NULL,
    FOREIGN KEY (PostalCode) REFERENCES LocationDetails ON DELETE CASCADE
);

CREATE TABLE Companies_CareerFairs
(
    CompanyId INTEGER,
    EventId   INTEGER,
    PRIMARY KEY (CompanyId, EventId),
    FOREIGN KEY (CompanyId) REFERENCES Companies ON DELETE CASCADE,
    FOREIGN KEY (EventId) REFERENCES CareerFairs ON DELETE CASCADE
);

CREATE TABLE JobSeekers_CareerFairs
(
    JobSeekerId VARCHAR(100),
    EventId     INTEGER,
    PRIMARY KEY (JobSeekerId, EventId),
    FOREIGN KEY (JobSeekerId) REFERENCES JobSeekers (UserName) ON DELETE CASCADE,
    FOREIGN KEY (EventId) REFERENCES CareerFairs ON DELETE CASCADE
);
