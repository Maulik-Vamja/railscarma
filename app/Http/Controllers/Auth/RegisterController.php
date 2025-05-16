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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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

        // $ip = request()->ip();
        $ip = '150.107.241.173';
        // Get the city from the IP address using MaxMind GeoIP database
        $reader = new \GeoIp2\Database\Reader(public_path('GeoLite2-City.mmdb'));
        try {
            $record = $reader->city($ip);
            $city = $record->city->name;
            $state = $record->mostSpecificSubdivision->name;
            $country = $record->country->name;
        } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
            $city = 'Unknown';
            $state = 'Unknown';
            $country = 'Unknown';
        } catch (\Exception $e) {
            $city = 'Unknown';
            $state = 'Unknown';
            $country = 'Unknown';
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'ip_address' => $ip,
            'password' => Hash::make($data['password']),
        ]);
    }
}
