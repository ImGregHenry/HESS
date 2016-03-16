######## CLOUD
CREATE DATABASE IF NOT EXISTS a4060350_HESS;
USE a4060350_HESS;

# CREATE USER IF NOT EXISTS 'a4060350_HESSADM'@'localhost' IDENTIFIED BY '***';GRANT ALL PRIVILEGES ON *.* TO 'a4060350_HESSADM'@'localhost' IDENTIFIED BY '***' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;GRANT ALL PRIVILEGES ON `a4060350_HESS`.* TO 'a4060350_HESSADM'@'localhost';

CREATE TABLE IF NOT EXISTS PeakType (
	PeakTypeID INTEGER PRIMARY KEY AUTO_INCREMENT,
	PeakName TEXT
);

INSERT INTO PeakType (PeakName)
VALUES ("OFF-PEAK" ),
("ON-PEAK"), 
("MID-PEAK-ENABLE"),
("MID-PEAK-DISABLE");

# OFF-PEAK = 1
# ON-PEAK = 2
# MID-PEAK-ENABLE = 3
# MID-PEAK-DISABLE = 4

CREATE TABLE IF NOT EXISTS WeekType (
	WeekTypeID INTEGER PRIMARY KEY AUTO_INCREMENT,
	WeekName TEXT
);

INSERT INTO WeekType (WeekName)
VALUES ("WEEKDAY"),
("WEEKEND");

# WEEKDAY = 1
# WEEKEND = 2

CREATE TABLE IF NOT EXISTS PeakSchedule (
	PeakScheduleID INTEGER PRIMARY KEY AUTO_INCREMENT,
	WeekTypeID INTEGER,
	PeakTypeID INTEGER,
	StartTime TIME,
	EndTime TIME,
	FOREIGN KEY (WeekTypeID) REFERENCES WeekType (WeekTypeID),
	FOREIGN KEY (PeakTypeID) REFERENCES PeakType (PeakTypeID)
);

# INSERT INTO PeakSchedule (WeekTypeID, PeakTypeID, StartTime, EndTime)
# VALUES (1, 2, "1:00:00", "8:00:00"),
# (1, 3, "8:00:01", "11:59:00");



CREATE TABLE IF NOT EXISTS BatteryStatus (
	BatteryStatusID INTEGER PRIMARY KEY AUTO_INCREMENT,
	PeakScheduleID INTEGER,
	DeviceID INTEGER,
	IsEnabled INTEGER,
	RecordTime DATETIME,
#	RecordTimeMS INTEGER,
	CloudRecordTime DATETIME,
#	CloudRecordTimeMS INTEGER,
#	PowerLevelValue DECIMAL,
	PowerLevelPercent DECIMAL
#	FOREIGN KEY (PeakScheduleID) REFERENCES PeakSchedule(PeakScheduleID)
);




CREATE TABLE IF NOT EXISTS PowerCost (
	PowerCostID INTEGER PRIMARY KEY AUTO_INCREMENT,
	PeakCost REAL,
	MidPeakCost REAL,
	OffPeakCost REAL
);

INSERT INTO PowerCost (PeakCost, MidPeakCost, OffPeakCost)
VALUES (17.5, 12.8, 8.3);


CREATE TABLE IF NOT EXISTS PowerUsage 
(	PowerUsageID INTEGER PRIMARY KEY AUTO_INCREMENT,
	PeakTypeID INTEGER,        
	RecordTime DATETIME,
	PowerUsageWatt DOUBLE,
	FOREIGN KEY (PeakTypeID) REFERENCES PeakType (PeakTypeID)
);

# INSERT INTO PowerUsage (PeakTypeID, RecordTime, PowerUsageWatt)
# VALUES (2, NOW(), 1.553);




#INSERT INTO BatteryStatus (PeakScheduleID, DeviceID, IsEnabled, RecordTime, PowerLevelValue, PowerLevelPercent)
#VALUES (0, 19, 1, NOW(), "12.6666", "99.999"),
#(0, 19, 1, NOW(), "12.5555", "98.999");

