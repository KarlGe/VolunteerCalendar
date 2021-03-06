CREATE TABLE volunteer (
	ID SMALLINT( 3 ) NOT NULL AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL,
	phoneNum VARCHAR(30),
	notes VARCHAR(255),
	gender CHAR(1),
	nationalityID INT(1),
	email VARCHAR(50),
	active BOOLEAN NOT NULL DEFAULT TRUE,
	PRIMARY KEY ( ID )
);
CREATE TABLE volunteerHistory(
	ID SMALLINT( 3 ) NOT NULL AUTO_INCREMENT,
	volunteerID SMALLINT( 3 ) NOT NULL,
	dateFrom DATE NOT NULL,
	dateTo DATE NOT NULL,
	contractSigned BOOLEAN DEFAULT FALSE,
	reg_date TIMESTAMP,
	active BOOLEAN NOT NULL DEFAULT TRUE,
	PRIMARY KEY ( ID )
);
CREATE TABLE volunteerTransactionHistory(
	ID SMALLINT( 3 ) NOT NULL AUTO_INCREMENT,
	periodID SMALLINT( 3 ) NOT NULL,
	transactionDate DATE NOT NULL,
	description VARCHAR(255),
	reg_date TIMESTAMP,
	amount INT(10),
	paidByVolunteer BOOLEAN NOT NULL DEFAULT TRUE,
	active BOOLEAN NOT NULL DEFAULT TRUE,
	PRIMARY KEY( ID )
);