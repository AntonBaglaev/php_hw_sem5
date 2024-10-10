CREATE TABLE application1.memory_log (
	id_memory_log INT NOT NULL AUTO_INCREMENT,
	user_agent VARCHAR(500) NULL,
	log_datetime DATETIME NULL,
	url TEXT NULL,
	memory_volume INT NULL,
	PRIMARY KEY (id_memory_log));