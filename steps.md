step1 : "laravel new blog"
step2: "php artisan make:auth" //user authentiation for customers/users (laravel predefined)
3: "create admins table with this command -- 'php artian make:migration create_admins_table --create=admins"
3.1: add table colums  id(primary), name(string), email(string,unique), job_title(string), password(string) , rememberToken
3.2 : add database name called multiuth
3.3 : edit env file to edit migration 
3.4 run commannd php artisan migrate