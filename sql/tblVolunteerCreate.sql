CREATE TABLE volunteer (
	ID SMALLINT( 3 ) NOT NULL AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL,
	phoneNum VARCHAR(30),
	notes VARCHAR(255),
	gender CHAR(1),
	nationalityID INT(1),
	email VARCHAR(50),
	active BOOLEAN NOT NULL,
	PRIMARY KEY ( ID )
);
CREATE TABLE volunteerHistory(
	ID SMALLINT( 3 ) NOT NULL AUTO_INCREMENT,
	volunteeeID SMALLINT( 3 ) NOT NULL,
	dateFrom DATE NOT NULL,
	dateTo DATE NOT NULL,
	moneyOwed INT(5),
	moneyPaid INT(5),
	contractSigned BOOLEAN,
	reg_date TIMESTAMP,
	active BOOLEAN NOT NULL,
	PRIMARY KEY ( ID )
);
