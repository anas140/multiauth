step1 : "laravel new multiauth"

step2: "php artisan make:auth" //user authentiation for customers/users (laravel predefined)

3: "create admins table with this command -- 'php artian make:migration create_admins_table --create=admins"
3.1: add table colums  id(primary), name(string), email(string,unique), job_title(string), password(string) , rememberToken
3.2 : add database name called multiuth
3.3 : edit env file to edit migration 
3.4 run commannd php artisan migrate

4   : create a admin model(copy the user model for authenticatable and notifiable class)
4.1 : add job_title to the fillable 
4.2 : create guards for admins (/config/auth.php)
		'admin' => [
			'driver' => 'session',
			'provider' => 'admins',
		]
4.2 : Set provider for admin 
		'admins' => [
			'driver' => 'elequent',
			'model' => 'App\Admin::class',
			]
4.3 : Protect guard in Admin model
		protected $guard = 'admin';		
