# mydoujinlist
Although doujins have gained popularity over the recent years, there is not a single platform for people to record their favourite doujins.<br>
Anime and manga have MyAnimeList, but doujins do not.<br>
It does not make sense.<br>
If nobody is going to do it, I will.<br>
Even if I cannot complete it by myself, I believe that someone else will carry on.<br>
I want to make MyDoujinList a reality, not just something I think to myself, "it would be nice if it existed".

## Setup web stack
MyDoujinList is built on a LAMP stack, which stands for Linux, Apache, MySQL, and PHP.<br>
It requires a Linux system, which can be installed into a virtual machine or obtained via dual boot.<br>
It also requires XAMPP, which is a package which contains the Apache web server, MySQL, PHP, Perl, a FTP server and phpMyAdmin.<br>
You can download XAMPP for Linux PHP 7.4 from https://www.apachefriends.org/download.html<br>
After downloading, use `chmod 755 <installer name>` to change permissions.<br>
Then you can run `./<installer name>` to run installer.<br>
After installing, to launch XAMPP, use `sudo /opt/lampp/lampp start`<br>
If you see an error like "netstat: command not found", you need to install Net Tools using `sudo apt install net-tools`<br>

## Setup code
Obtain code by using `git clone https://github.com/NotTheRealEdmund/mydoujinlist.git`<br>
If you do not have git, you can download the ZIP file from github and extract it.<br>
You should have a folder which is named `mydoujinlist`, which has a lot of files inside.<br>
Move that folder to the directory `/opt/lampp/htdocs` by using `sudo mv <current directory> </opt/lampp/htdocs>`<Br>
You can now view the webpage, the login page, via `localhost/mydoujinlist` in your browser.<br>
However, you cannot login without first setting up the database.<br>

## Setup database
Go to `localhost` in your browser, click on phpMyAdmin button on the top right corner.<br>
Create a database named `mydoujinlist`<br>
Click on the SQL button.<br>
Paste the code below and press Go.<br>
```
CREATE TABLE `mydoujinlist`.`accounts` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `username` VARCHAR(50) NOT NULL , `password` VARCHAR(255) NOT NULL , `email` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
```
Now paste the code below to create a user account with username: test and password: test.<br>
The long string in the middle is the hashed password.<br>
Hashing is a one-way irreversible technique to convert plaintexts into digests, which are alphanumeric strings which will be used for verification.<br>
```
INSERT INTO `accounts` (`id`, `username`, `password`, `email`) VALUES (1, 'test', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'password_is_test@test.com');
```

