CREATE TABLE `user` (
    `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `phone` VARCHAR(191) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `password` VARCHAR(191) NOT NULL,
    CONSTRAINT user_email_uk UNIQUE(email),
    CONSTRAINT user_id_pk PRIMARY KEY(id)
);

CREATE TABLE `administrator`(
  `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`user_id` INT UNSIGNED NOT NULL,
   CONSTRAINT administrator_id_pk PRIMARY KEY(id),
   CONSTRAINT administrator_user_id_fk FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `researcher`(
  `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`user_id` INT UNSIGNED NOT NULL,
   CONSTRAINT researcher_id_pk PRIMARY KEY(id),
   CONSTRAINT researcher_user_id_fk FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `reviewer`(
  `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`user_id` INT UNSIGNED NOT NULL,
   CONSTRAINT reviewer_id_pk PRIMARY KEY(id),
   CONSTRAINT reviewer_user_id_fk FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `editor`(
  `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`user_id` INT UNSIGNED NOT NULL,
   CONSTRAINT editor_id_pk PRIMARY KEY(id),
   CONSTRAINT editor_user_id_fk FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);
