<?php
namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Prospect;
use App\Models\Training;
use App\Models\UserTrainingActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthenticateController extends BaseController {

    public function index(Request $request) {

        return view('admin.auth.login', array('title' => 'Login'));
    }

    public function loginValidate(Request $request) {

        $this->validator($request);

        // Find user by email
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            // User not found
            return response()->json([
                'status' => 'FALSE',
                'message' => 'User not found. Please check your email or register an account.'
            ]);
        }

        // Attempt to authenticate the user
        if (Auth::guard('backend')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            // Authentication passed...

            // Get the authenticated user
            $user = Auth::guard('backend')->user();

            // Check if the user's status is active
            if ($user->status !== 1) {
                // If the user's status is not active, log them out and return a failure response
                Auth::guard('backend')->logout();

                return response()->json([
                    'status' => 'FALSE',
                    'message' => 'Your account is not active. Please contact support.'
                ]);
            }

            // Proceed if the user is active
            Session::flash('success-message', "Welcome to 4uv dashboard!");
            return response()->json([
                'status' => 'TRUE',
                'redirect_url' => config('constants.ADMIN_URL').'dashboard'
            ]);
        }

        // Authentication failed...
        return $this->loginFailed();
    }

    /**
     * Validate the form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:users|min:5|max:191',
            'password' => 'required|string|min:6|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
         return response()->json([
            'status' => 'FALSE',
            'message' =>'"These credentials do not match our records'
        ]);
    }

    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
        return response()->json([
            'status' => 'FALSE',
            'redirect_url' => config('constants.ADMIN_URL').'login'
        ]);

    }

   /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('backend')->logout();
        return redirect(config('constants.ADMIN_URL').'login');
    }


    /**
     * Add user the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addUser(Request $request)
    {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users', 'regex:/(.+)@(.+)\.(.+)/i'],
            'password' => 'required',
            'dob' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {

            return json_encode( $validator->errors());

        } else {

            $user = new User();
            $user->name = $request->firstname.' '.$request->lastname;
            $user->firstname = $request->firstname;
            $user->lastname =  $request->lastname;
            $user->email =  $request->email;
            $user->dob =  $request->dob;
            $user->phone =  $request->phone;
            $user->feature_access = '1';

            $user->password =   isset($request->password) ? Hash::make($request->password) : '';

            $user->save();

            Session::flash('success-message',  "User created successfully! Plase login");

            $data['status'] = 'TRUE';

            return response()->json($data);
        }
    }

//    public function myProfile(Request $request) {
//        $inputs = $request->all();
//        $user = Auth::guard('backend')->user();
//
//        return view('admin.auth.myprofile', array('title' => 'My Profile','user' => $user));
//    }
    public function updateProfile(Request $request) {
        $user = Auth::guard('backend')->user();
        $title = 'My Profile';
        $inputs  = $request->all();

        if ($user || !empty($user)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|unique:users,email,"'.$user->id.'"',
                    'dob' => 'required',
                    'phone' => 'required|digits:10|numeric',
                    'pincode' => 'digits:6|numeric',
                ]);

                if ($validator->fails()) {
                    //return json_encode($validator->errors());
                    return response()->json(['errors' => $validator->errors()], 422);
                } else {
                    $user->name = $request->firstname.' '.$request->lastname;
                    $user->firstname = $request->firstname;
                    $user->lastname =  $request->lastname;
                    $user->email =  $request->email;
                    $user->dob =  $request->dob;
                    $user->phone =  $request->phone;
                    $user->feature_access = '1';
                    $user->city = $request->city;
                    $user->state = $request->state;
                    $user->country = $request->country;
                    $user->address1 = $request->address1;
                    $user->address2 = $request->address2;
                    $user->pincode = $request->pincode;
                    $user->status =isset($request->status) && $request->status == 1 ? 1: 0;

                    $user->save();


                    if ($user->save()) {
                        Session::flash('success-message',  " profile updated successfully !");
                        $data['success'] = true;
                        $data['redirect_url'] = route('backend.dashboard');

                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " User updated successfully !");
                }
            } else {
                return view('admin.auth.myprofile', compact('user', 'title'));

            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'dashboard');
    }


    public function sendResetLinkEmail(Request $request) {
         //$this->validateEmail($request);
         $user = DB::table('users')->where('email', '=', $request->email)->first();

         if (!$user) {
            $data['status'] = 'false';
            Session::flash('success-message', 'User Not Found');
            return redirect()->back()->with("success", " User Not Found !");
         }
         $userArray = json_decode(json_encode($user), true);
         //get password reset token
         DB::table('password_reset_tokens')->insert([
             'email' => $request->email,
             'token' => Str::random(60),
             'created_at' => Carbon::now()
         ]);
         $tokenData = DB::table('password_reset_tokens')->where('email', $request->email)->first();


         $event_name="Reset Password";
         $data['name'] = $user->firstname;
         $data['link'] = config('constants.ADMIN_URL').'password/reset/'.Str::random(60).'?email='.$request->email;
         $data['site_url'] = config('constants.ADMIN_URL');

         $to      = $request->email;
         $subject = 'Request for password reset';
         $message = 'hello';
         $headers = 'From: noreply@thelastmiddleclass.com'       . "\r\n" .
                      'Reply-To: noreply@thelastmiddleclass.com' . "\r\n" .
                      'X-Mailer: PHP/' . phpversion();

         //Mail::to($request->email)->send($to, $subject, $message, $headers);
         Mail::to($request->email)->send(new ResetPasswordMail($userArray, $data, $subject, $message, $headers));
         //$this->sendKlaviyoEmail($request->email, $event_name, $data);

         $data['status'] = 'true';
         Session::flash('success-message', 'Password reset Mail send successfully');
         return redirect()->back()->with("success", " Distributor added successfully !");
        // return response()->json($data);
    }
    public function showResetLinkForm(Request $request, $token) {
        return view('admin.auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function updatePassword(Request $request) {
        // var_dump($request->email);
        // exit;
        $request->validate([
            'email' => 'required|email|exists:users,email', // Adjust table if needed
            'token' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6',
        ]);

        // Fetch the reset record from the database
            $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

            // Check if the token exists and is valid
            if (!$resetRecord) {
                return back()->withErrors(['email' => 'Invalid or expired reset token.']);
            }

            // Update the user's password
            User::where('email', $request->email)->update([
                'password' => Hash::make($request->password),
            ]);

            // Delete the token after successful password reset
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            Session::flash('success-message', 'Password Change successfully');
            return redirect()->route('backend.login')->with('status', 'Password reset successfully. You can now login.');
    }

    public function dashboard() {

//        $title = 'Dashboard';
//        //$distributorCount = User::where('type', 'Distributor')->count();
//        $distributorCount = User::role('Distributor')
//            ->where('upline_id', auth()->id())
//            ->where('id', '!=', auth()->id())
//            ->count();
//        $prospectCount = Prospect::count();
//        $demoCount = Training::count();
//
//        $trainingsAttended = UserTrainingActivity::where('user_id', $userId = auth()->id())
//            ->distinct('training_id')
//            ->count('training_id');
//
//        // Calculate remaining trainings
//        $trainingsToBeAttended = $demoCount - $trainingsAttended;
//        $trainingsToBeAttended = max($trainingsToBeAttended, 0);
//        //return view('admin.auth.dashboard',compact('title'));
//        return view('admin.auth.dashboard', compact(
//            'title',
//            'distributorCount',
//            'prospectCount',
//            'demoCount',
//            'trainingsAttended',
//            'trainingsToBeAttended'
//        ));
        $title = 'Dashboard';
        $authUser = auth()->user();

        // Default counts
        $distributorCount = 0;
        $prospectCount = 0;
        $demoCount = 0;

        // Distributor count logic
        if (!$authUser->hasRole('Administrator')) {
            $distributorCount = User::role('Distributor')
                ->where('upline_id', $authUser->id)
                ->where('id', '!=', $authUser->id)
                ->count();
        } else {
            $distributorCount = User::role('Distributor')->count();
        }

        // Prospect count filtering based on role
        if ($authUser->hasRole('Administrator')) {
            $prospectCount = Prospect::count(); // Show all prospects
            $demoCount = Training::count(); // Show all trainings
        } else {
            $prospectCount = Prospect::where('created_by', $authUser->id)->count();
            $demoCount = Training::where('created_by', $authUser->id)->count();
        }

        // Trainings attended by the logged-in user
        $trainingsAttended = UserTrainingActivity::where('user_id', $authUser->id)
            ->distinct('training_id')
            ->count('training_id');

        // Calculate remaining trainings
        $trainingsToBeAttended = max($demoCount - $trainingsAttended, 0);

        return view('admin.auth.dashboard', compact(
            'title',
            'distributorCount',
            'prospectCount',
            'demoCount',
            'trainingsAttended',
            'trainingsToBeAttended'
        ));
    }

    public function displayChangePasswordForm(Request $request){
        $user = Auth::user();
        return view('auth.change_password',compact('user'));
    }
 }
