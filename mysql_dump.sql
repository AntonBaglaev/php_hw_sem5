CREATE TABLE application1.users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    user_login VARCHAR(45),
    user_name VARCHAR(45),
    user_lastname VARCHAR(45),
    user_birthday_timestamp INT,
    user_password_hash VARCHAR(255),
    user_role VARCHAR(255))
    DEFAULT CHARACTER SET = utf8;