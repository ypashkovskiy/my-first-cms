CREATE TABLE subcategories (
    id int NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
    categories_id smallint unsigned NOT NULL, 
    name varchar(255) NOT NULL,    
    description text NOT NULL, 
   FOREIGN KEY  (categories_id) REFERENCES categories (id)  
                 
);
ALTER TABLE articles
ADD subcategoryId INT;

-- 2. Добавить внешнее ограничение
ALTER TABLE articles
ADD CONSTRAINT fk_articles_subcategories
FOREIGN KEY (subcategoryId) REFERENCES subcategories (id)
ON DELETE SET NULL

ALTER TABLE articles MODIFY categoryId INT NULL;
