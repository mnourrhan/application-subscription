<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\OperatingSystemsEnum;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RegisteringDeviceService;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Auth
 */
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var JWTAuth
     */
    private $jwt = NULL;

    /**
     * @var RegisteringDeviceService
     */
    private $service = NULL;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt, RegisteringDeviceService $service)
    {
        $this->jwt = $jwt;
        $this->service = $service;
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
            'uid' => ['required', 'string', 'max:169'],
            'app_id' => ['required', 'string', 'max:169'],
            'language' => ['required', 'string', 'max:50'],
            'operating_system' => ['required', 'string', 'max:20',
                'in:' . implode(",", OperatingSystemsEnum::OPERATING_SYSTEMS)],
        ]);
    }

    /**
     * @param array $data
     * @return
     */
    protected function create(array $data)
    {
        $attributes = ['uid' => $data['uid'], 'app_id' => $data['app_id']];
        $data = [
            'uid' => $data['uid'],
            'app_id' => $data['app_id'],
            'language' => $data['language'],
            'operating_system' => $data['operating_system']
        ];
        $device = $this->service->execute($attributes, $data);
        return $device;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($device = $this->create($request->all())));

        $token = $this->jwt->fromUser($device);
        $data = ['token' => $token];
        return $request->wantsJson()
            ? successResponse(__('Registered successfully'), $data)
            : redirect($this->redirectPath());
    }
}
