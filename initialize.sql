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
VALUES ('john_doe', 'John Doe', 'john.doe@email.com', '123-456-7890', 'Description of John');
INSERT INTO Users
VALUES ('jane_smith', 'Jane Smith', 'jane.smith@email.com', '234-567-8901', 'Description of Jane');
INSERT INTO Users
VALUES ('michael_johnson', 'Michael Johnson', 'michael.johnson@email.com', '345-678-9012', 'Description of Michael');
INSERT INTO Users
VALUES ('emily_brown', 'Emily Brown', 'emily.brown@email.com', '456-789-0123', 'Description of Emily');
INSERT INTO Users
VALUES ('william_davis', 'William Davis', 'william.davis@email.com', '567-890-1234', 'Description of William');
INSERT INTO Users
VALUES ('olivia_wilson', 'Olivia Wilson', 'olivia.wilson@email.com', '678-901-2345', 'Description of Olivia');
INSERT INTO Users
VALUES ('james_miller', 'James Miller', 'james.miller@email.com', '789-012-3456', 'Description of James');
INSERT INTO Users
VALUES ('ava_jones', 'Ava Jones', 'ava.jones@email.com', '890-123-4567', 'Description of Ava');
INSERT INTO Users
VALUES ('robert_lee', 'Robert Lee', 'robert.lee@email.com', '901-234-5678', 'Description of Robert');
INSERT INTO Users
VALUES ('sophia_taylor', 'Sophia Taylor', 'sophia.taylor@email.com', '012-345-6789', 'Description of Sophia');


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
VALUES ('john_doe', 1);
INSERT INTO Recruiters
VALUES ('jane_smith', 2);
INSERT INTO Recruiters
VALUES ('michael_johnson', 3);
INSERT INTO Recruiters
VALUES ('emily_brown', 4);
INSERT INTO Recruiters
VALUES ('william_davis', 5);

INSERT INTO JobSeekers
VALUES ('olivia_wilson');
INSERT INTO JobSeekers
VALUES ('james_miller');
INSERT INTO JobSeekers
VALUES ('ava_jones');
INSERT INTO JobSeekers
VALUES ('robert_lee');
INSERT INTO JobSeekers
VALUES ('sophia_taylor');
--
INSERT INTO JobPosts
VALUES (1, 'john_doe', 'Software Engineer', 'Online', 80000, TO_DATE('2023-10-18', 'YYYY-MM-DD'), 'Full-time',
        'We are looking for a software engineer with strong programming skills.', TO_DATE('2023-11-15', 'YYYY-MM-DD'),
        'Bachelor''s degree in Computer Science, Proficiency in Java, 2+ years of experience', 50);
INSERT INTO JobPosts
VALUES (2, 'jane_smith', 'Marketing Manager', '456 Elm Avenue, Toronto', 70000, TO_DATE('2023-10-19', 'YYYY-MM-DD'), 'Full-time',
        'We need an experienced marketing manager to lead our marketing team.', TO_DATE('2023-11-20', 'YYYY-MM-DD'),
        'Bachelor''s degree in Marketing, 5+ years of marketing experience', 20);
INSERT INTO JobPosts
VALUES (3, 'michael_johnson', 'Data Analyst', '123 Main Street, Vancouver', 30000, TO_DATE('2023-10-19', 'YYYY-MM-DD'), 'Internship',
        'We are hiring a data analyst intern for a short-term project.', TO_DATE('2023-11-10', 'YYYY-MM-DD'),
        'Strong data analysis skills, familiarity with Python and database', 30);
INSERT INTO JobPosts
VALUES (4, 'emily_brown', 'Graphic Designer', 'Online', 55000, TO_DATE('2023-10-21', 'YYYY-MM-DD'), 'Full-time',
        'Looking for a creative graphic designer to work on various design projects.',
        TO_DATE('2023-11-25', 'YYYY-MM-DD'), 'Graphic design experience, proficiency in Adobe Creative Suite', 12);
INSERT INTO JobPosts
VALUES (5, 'william_davis', 'Customer Support Representative', '101 Pine Road, Calgary', 45000, TO_DATE('2023-10-22', 'YYYY-MM-DD'),
        'Full-time', 'We are seeking a customer support representative to assist our customers.',
        TO_DATE('2023-11-30', 'YYYY-MM-DD'), 'Excellent communication skills, customer service experience', 15);


INSERT INTO Resumes
VALUES ('http://example.com/resume1-olivia_wilson', 'olivia_wilson');
INSERT INTO Resumes
VALUES ('http://example.com/resume1-james_miller', 'james_miller');
INSERT INTO Resumes
VALUES ('http://example.com/resume1-ava_jones', 'ava_jones');
INSERT INTO Resumes
VALUES ('http://example.com/resume1-robert_lee', 'robert_lee');
INSERT INTO Resumes
VALUES ('http://example.com/resume1-sophia_taylor', 'sophia_taylor');

INSERT INTO Applications
VALUES (1, 'john_doe', 1, TO_DATE('2023-10-19', 'YYYY-MM-DD'), 'http://example.com/coverletter1-olivia_wilson', 'http://example.com/resume1-olivia_wilson',
        'Under Review', TO_DATE('2023-10-20', 'YYYY-MM-DD'));
INSERT INTO Applications
VALUES (2, 'jane_smith', 2, TO_DATE('2023-10-20', 'YYYY-MM-DD'), NULL, 'http://example.com/resume1-james_miller', 'Interviewing',
        TO_DATE('2023-10-20', 'YYYY-MM-DD'));
INSERT INTO Applications
VALUES (3, 'michael_johnson', 3, TO_DATE('2023-10-21', 'YYYY-MM-DD'), 'http://example.com/coverletter1-ava_jones', 'http://example.com/resume1-ava_jones',
        'Interviewing', TO_DATE('2023-10-22', 'YYYY-MM-DD'));
INSERT INTO Applications
VALUES (4, 'emily_brown', 4, TO_DATE('2023-10-30', 'YYYY-MM-DD'), 'http://example.com/coverletter1-robert_lee', 'http://example.com/resume1-robert_lee',
        'Accepted', TO_DATE('2023-10-30', 'YYYY-MM-DD'));
INSERT INTO Applications
VALUES (5, NULL, NULL, TO_DATE('2023-10-10', 'YYYY-MM-DD'), 'http://example.com/coverletter1-sophia_taylor',
        'http://example.com/resume1-sophia_taylor', 'Incomplete application', NULL);

INSERT INTO ScheduledInterviews
VALUES (1, 1, '123 Main St, City1', 'In-Person', TO_DATE('2023-10-28T10:00', 'YYYY-MM-DD"T"HH24:MI'), 'PST');
INSERT INTO ScheduledInterviews
VALUES (2, 2, '143 Main St, City2', 'In-Person', TO_DATE('2023-10-25T11:00', 'YYYY-MM-DD"T"HH24:MI'), 'EST');
INSERT INTO ScheduledInterviews
VALUES (3, 3, 'https://zoom.us/j/123', 'Online', TO_DATE('2023-10-15T13:00', 'YYYY-MM-DD"T"HH24:MI'), 'ADT');
INSERT INTO ScheduledInterviews
VALUES (4, 4, '193 University St, City4', 'In-Person', TO_DATE('2023-10-25T10:00', 'YYYY-MM-DD"T"HH24:MI'), 'CDT');
INSERT INTO ScheduledInterviews
VALUES (5, 5, 'https://zoom.us/j/456', 'Online', TO_DATE('2023-10-10T14:30', 'YYYY-MM-DD"T"HH24:MI'), 'MST');

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
VALUES (1, 1, 'Anna', '111-111-2222');
INSERT INTO Interviewers_Attend
VALUES (2, 2, 'Jone', '222-222-3333');
INSERT INTO Interviewers_Attend
VALUES (3, 3, 'Sandrew', '333-333-4444');
INSERT INTO Interviewers_Attend
VALUES (4, 4, 'Peter', '444-444-5555');
INSERT INTO Interviewers_Attend
VALUES (5, 5, 'Jack', '555-555-6666');

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
VALUES (1, 'BusinessCareerFair2023', 'V6T1Z1', 'University of British Columbia', TO_DATE('2023-10-10', 'YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (2, 'TechnicalCareerFair2024', 'M5R0A3', 'University of Toronto', TO_DATE('2024-01-10', 'YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (3, 'EngineerCareerFair2024', 'T6G2R3', 'University of Alberta', TO_DATE('2024-09-10', 'YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (4, 'TechnicalCareerFair2023', 'V6T1Z4', 'University of British Columbia', TO_DATE('2023-05-10', 'YYYY-MM-DD'));
INSERT INTO CareerFairs
VALUES (5, 'EngineerCareerFair2023', 'V6T1Z2', 'University of British Columbia', TO_DATE('2023-05-10', 'YYYY-MM-DD'));

INSERT INTO Companies_CareerFairs
VALUES (1, 1);
INSERT INTO Companies_CareerFairs
VALUES (2, 2);
INSERT INTO Companies_CareerFairs
VALUES (3, 3);
INSERT INTO Companies_CareerFairs
VALUES (4, 4);
INSERT INTO Companies_CareerFairs
VALUES (5, 5);

INSERT INTO JobSeekers_CareerFairs
VALUES ('olivia_wilson', 1);
INSERT INTO JobSeekers_CareerFairs
VALUES ('james_miller', 2);
INSERT INTO JobSeekers_CareerFairs
VALUES ('ava_jones', 3);
INSERT INTO JobSeekers_CareerFairs
VALUES ('robert_lee', 4);
INSERT INTO JobSeekers_CareerFairs
VALUES ('sophia_taylor', 5);
