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

5.1 : Create a route for admin(routes/web.php)
		Route::get('admin', 'AdminController@index');
5.2 : Create a controller for Admin-AdminController(duplicate home controller)
5.3 : Create view for index method : (duplicate home.blade.php)

6.1 : Create a controller for AdminLogin 'php artisan make:controller Auth/AdminLoginController' 
	6.1.1 :  public function showLoginForm() }{
				$this->load->view('auth.admin-login')
		 	}
			public function login() {
				return true;
			}		 	
6.2  : Make view for admin-login (duplicate user login)		
6.3 :  Create Routes for Admin Login for get Login form and Post Login Form
	Route::get('admin/login',...@shoLoginForm)->name('admin.login');
	Route::post('admin/login', ...@login)->name('admin.submit');
6.4 Rote Admin Route groups
		Route::prefix('admin')->group(function() {
			//insert all group routes
			//remove all admin names
		})
6.5 : Create a middleware for admin
	@AdminLoginController
	6.5.1 : 
		public function __construct() {
			$this->middleware('guest:admin');
		}		
6.6 : post loginform
		public function login(Request $request) {
		//
		}
	6.6.1 : #Validate the form data
		$this->validate($request, [
			'email' => 'requires|email',
			'password' => 'requires|min:6'
		]);
	6.6.2 : # Attempt to Login Admin
		import use Auth facede
		use Auth;
		//Auth::guard('admin')->attempt($credentials, $remember);
		if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password],  $request->remember)) {
		//6.6.3 : # if(success) then redurect to the intended location
			return redirect()->intended(route('admin.dashboard'));
		}	
	
	6.64 :  # id(!suceess) then redirect to the login with the form data
	 return redirect()->back()->withInput($request->only('email', 'remember'));
6.7 : Create an Admin
		php artisan tinker 
		$admin = new App\Admin
		$admin->name = "anasummalil"
		$admin->email = 'muhammedanas4140@gmail.com',
		$admin->password = Hash::make('password')
		$admin->job_title = 'admin'
		$admin->save();

7: 