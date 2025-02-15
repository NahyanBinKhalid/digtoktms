<?php

namespace App\Repositories;

use App\Mail\EmployeeRegistration;
use App\Mail\ForgotPassword;
use App\Models\Country;
use App\Models\Role;
use App\Models\Status;
use App\Repositories\Interfaces\AuthInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthRepository extends BaseRepository implements AuthInterface {
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register($user) 
    {
        try {
            DB::beginTransaction();

            if (isset($user['country_uuid']) && ! is_null($user['country_uuid'])) {
                $nationality = Country::where('uuid', $user['country_uuid'])->first();
                $user['country_id'] = $nationality->id;
                $user['country_uuid'] = $nationality->uuid;
            }

            $this->model->fill($user)->save();
            $this->model->assignRole(Role::where('name', $user['role'])->first()->name);
            
            $newUser = $this->model->with(['nationality', 'roles'])->find($this->model->id);
            $newUser->token = $newUser->createToken('accessToken')->plainTextToken;
            DB::commit();

            Mail::to($newUser->email)->send(new EmployeeRegistration($newUser, $user['password']));

            return [
                'error'     =>  false,
                'message'   =>  'New User Registered',
                'data'      =>  $newUser,
                'code'      =>  200
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }

    public function login($credentials)
    {
        try {
            if (Auth::attempt($credentials)) {
                $user = $this->model->with(['roles'])->find(Auth::user()->id);
                $user['token'] = $user->createToken('accessToken')->plainTextToken;
                // if(is_null($user->email_verified_at))
                // {
                //     return [
                //         'error'     =>  true,
                //         'message'   =>  'Email not verified. Please verify your email address.',
                //         'data'      =>  null,
                //         'code'      =>  401
                //     ];
                // }
                
                return [
                    'error'     =>  false,
                    'message'   =>  'You are successfully logged in.',
                    'data'      =>  $user,
                    'code'      =>  200
                ];
            } else {
                return [
                    'error'     =>  true,
                    'message'   =>  'Unauthorised',
                    'data'      =>  null,
                    'code'      =>  401
                ];
            }
        } catch (\Exception $exception) {
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }


    public function forgot($email) 
    {
        try {
            DB::beginTransaction();

            $otp = rand(111111, 999999);

            $user = $this->model->where('email', $email)->first();
            $user->fill([
                'otp'           =>  $otp,
                'otp_timestamp' =>  Carbon::now()
            ])->save();
            DB::commit();

            Mail::to($email)->send(new ForgotPassword($user, $otp));

            return [
                'error'     =>  false,
                'message'   =>  'Forgot OTP sent to your email',
                'data'      =>  $user,
                'code'      =>  200
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }


    public function reset($data) 
    {
        try {
            DB::beginTransaction();

            $user = $this->model->where('otp', $data['otp'])->first();
            $user->fill([
                'otp'           =>  null,
                'otp_timestamp' =>  null,
                'password'      =>  $data['password']
            ])->save();
            DB::commit();

            return [
                'error'     =>  false,
                'message'   =>  'Password Reset Successfull',
                'data'      =>  $user,
                'code'      =>  200
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }

    public function profile()
    {
        try {
            $auth = auth()->user();
            $employee = User::with(['nationality', 'roles', 'employeeJobs'])->where('uuid', $auth->uuid)->first()->toArray();
            $employee['jobs'] = $employee['employee_jobs'];
            unset($employee['employee_jobs']);
            foreach ($employee['jobs'] as $key => $job) {
                $employee['jobs'][$key]['status'] = Status::where('uuid', $job['pivot']['status_uuid'])->first();
            }
            
            return [
                'error'     =>  false,
                'message'   =>  'User Profile',
                'data'      =>  $employee,
                'code'      =>  200
            ];
        } catch (\Exception $exception) {
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }

    public function update(array $data)
    {
        try {
            $auth = auth()->user();
            if (isset($data['country_uuid']) && ! is_null($data['country_uuid'])) {
                $country = Country::where('uuid', 'country_uuid')->first();
                if (! is_null($country)) {
                    $data['country_id'] = $country->id;
                    $data['country_uuid'] = $country->uuid;
                }
            }
            $user = User::with(['nationality', 'roles', 'employeeJobs'])->where('uuid', $auth->uuid)->first();
            $user->fill($data)->save();
            $user = $user->toArray();
            $user['jobs'] = $user['employee_jobs'];
            unset($user['employee_jobs']);
            foreach ($user['jobs'] as $key => $job) {
                $user['jobs'][$key]['status'] = Status::where('uuid', $job['pivot']['status_uuid'])->first();
            }
            
            return [
                'error'     =>  false,
                'message'   =>  'User Profile Updated',
                'data'      =>  $user,
                'code'      =>  200
            ];
        } catch (\Exception $exception) {
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }

    public function logout()
    {
        try {
            Auth::user()->tokens()->delete();
            return [
                'error'     =>  false,
                'message'   =>  'You are successfully logged out.',
                'data'      =>  null,
                'code'      =>  200
            ];
        } catch (\Exception $exception) {
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }

    public function upload($path, $file)
    {
        try {
            $fileName = time() . '.' . Str::lower($file->getClientOriginalExtension());
            $newFile = $this->uploads($path, $file, $fileName, 'root');
            return [
                'error'     =>  false,
                'message'   =>  'File Uploaded Successfully',
                'data'      =>  $newFile,
                'code'      =>  200
            ];
        } catch (\Exception $exception) {
            return [
                'error'     =>  true,
                'message'   =>  env('APP_ENV') == 'local' ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : 'Something went Wrong',
                'data'      =>  $exception,
                'code'      =>  $exception->getCode() > 0 && $exception->getCode() < 600 ? $exception->getCode() : 500
            ];
        }
    }
}
