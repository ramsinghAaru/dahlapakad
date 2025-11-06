<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s-]+$/u'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:30', 'unique:users'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'terms' => ['required', 'accepted'],
        ], [
            'name.regex' => 'The name may only contain letters, spaces, and hyphens.',
            'username.alpha_dash' => 'The username may only contain letters, numbers, dashes, and underscores.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'terms.required' => 'You must accept the terms and conditions.',
            'terms.accepted' => 'You must accept the terms and conditions.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Generate a random avatar color based on the username
        $colors = ['#3490dc', '#6574cd', '#9561e2', '#f66d9b', '#f6993f', '#38c172', '#4dc0b5', '#6cb2eb'];
        $color = $colors[rand(0, count($colors) - 1)];
        
        // Create the user with initial stats
        $user = User::create([
            'name' => $data['name'],
            'username' => strtolower($data['username']), // Store usernames in lowercase
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($data['name']) . 
                       '&background=' . substr($color, 1) . '&color=fff&size=128',
            'rating' => 1000, // Default starting rating
            'games_played' => 0,
            'games_won' => 0,
            'highest_rating' => 1000,
            'rank' => 'Beginner',
            'last_rank_update' => now()
        ]);
        
        // You might want to send a welcome email here
        // $this->sendWelcomeEmail($user);
        
        return $user;
    }
}
