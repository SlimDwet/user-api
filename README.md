# user-api
An API for users datas access

1 - Execute the SQL file
2 - composer install

Use this following command for unit tests:
./vendor/bin/phpunit ./tests/

Authorized fields :
- firstname
- lastname
- birthday (YYYY-MM-DD)
- email

URLs
====
get all users : /index.php?method=get_all
get an user : /index.php?method=get&id=<User ID>
create an user : /index.php?method=add (Send data by POST. All fields are required)
update an user : /index.php?method=update&id=<User ID>(Send data by POST. Only send desired fields)
delete an user : /index.php?method=delete&id=<User ID>
