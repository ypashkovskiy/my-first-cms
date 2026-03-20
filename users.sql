CREATE TABLE Users (
    UserID int NOT NULL  AUTO_INCREMENT  PRIMARY KEY , -- Поле 1: ID пользователя (уникальный)
    Username varchar(50) NOT NULL,    -- Поле 2: Имя пользователя (строка)
    Password CHAR(60) NOT NULL,                         
    Active TINYINT(1) NOT NULL DEFAULT 1             
);
