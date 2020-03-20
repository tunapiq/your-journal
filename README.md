# Installation Guide
- ensure you have php ~7.2.0 installed.
- install xampp with mysql (see youtube for help if needed)
- lookup how to setup a website in localhost using xampp on youtube
- create a mysql database with any name you like
- run the queries in the DATABASE.sql file on the database
- go to controllers/config.php and set your $db_name, $db_username, $db_pass
- ensure the username you input has permissions to access the mysql table you created


# Login Guide
- manually insert a new *user* into the `user` table on the database
- got to the `administrator` table and insert the `user_id` of the `user` you just created in there.
- go to the login page and login as the user
