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

##7: Exception
# We have our logins working as intended and are able to log in and out as our different users. It finally feels like the app is coming together. We have a Users model and Admins model, tracking different types of users independently. 
# Now we simply need to fix a few weird occurrences. The first problem we have is that if we ever try to go to our Admin center when we are not logged in, it redirects us to the Users Login Form. This is not right, we would expect the app to redirect us to the Admin Login Form so that we can log in as an Admin. We will fix this part first. This is can be handled in our exceptions handler. 
# In the exceptions handler we will get the guard that triggered the exception and then compare it to the guards in our app. Since we only have two (non-api) guards this will be easy. Check to see if it is admin and if it isn't, then it must be the web guard. We will set our named route for each login form and then redirect to the one that gets triggered. 
# The second problem is that when our "Guest" middleware notices a logged in user, it always redirects us to the /home location. This is ok when we are accessing a users guest path, but if we are trying to access an admin guest path, this is very unexpected. The more natural concept would be to redirect us to the admin dashboard. 
# We can edit this functionality in the RedirectIfAuthenticated Middleware. Just like before, we use our guard and test if it is either admin or web. Then redirect to the correct url for that guard's dashboard.
# 7.1 
	add this class in app->exceptions->Handler.php
		use Exception;
		use Request;
		use Illuminate\Auth\AuthenticationException;
		use Response;

# 8 . Logout functionality for Users an Admin
	8.1 : AdminLoginController add logout method
			1. specify the guard and add logout
				Auth::guard('admin')->logout();
			2: return rediirecg('/');
	8.2 : Add Logout for Users
			Inside LoginController
			8.2.1 : add logout method userLogout()
				Auth::guard('web')->logout();
				return redirect('/')
	8.3 Add routes for logout
		8.3.1 : inside admin prefix
			Route::get('/logout	', Auth\AdminController@logout)->name('admin.logout');
		8.3.2 : userLogout
			Route::get('users/logout', Auth\LoginController@userLogout)->name('user.logout');

		8.3.3: inside AdminLoginContrroller
		change
			$this->middleware('guest:admin', ['except' => ['logout']]);
		8.3.4 inside the LoginController
		change
			$this->middleware('guest', ['except' => ['loguot', userLogout]])
