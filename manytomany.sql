CREATE TABLE article_user (
    article_id smallint unsigned NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (article_id, user_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(UserID) ON DELETE CASCADE
);
