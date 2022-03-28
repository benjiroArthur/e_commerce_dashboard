<?php

namespace App\Http\Controllers;

use App\Jobs\NewUserNotifyJob;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        //get all users from the system paginated
        $users = User::paginate(10);
        if($request->has('trashed')){
            $users = User::onlyTrashed()->paginate(10);
        }
        $userTypes = UserType::all();
        return $request->wantsJson()
            ? new JsonResponse(['users' => $users, 'userTypes' => $userTypes], 200)
            : view('pages.users.index', compact('users', 'userTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'email' => 'required|unique:users|email',
            'gender' => 'required',
            'phone_number' => 'required',
            'user_type_id' => 'required',
        ]);

        try{
             DB::beginTransaction();

            //dd($request->all());
        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz@#$%^&"), 0, 8);
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'gender' => $request->gender,
            'password' => Hash::make($password),
            'phone_number' => $request->phone_number,
            'user_type_id' => $request->user_type_id
        ]);
        $emailData = [
            'user' => $user,
            'password' => $password,
            'message' => 'Please Login with the following credentials',
        ];
        DB::commit();
        NewUserNotifyJob::dispatch($emailData);
        return $request->wantsJson()
        ? new JsonResponse($user, 200)
        : back()->with('success', 'User Created Successfully');
        } catch(\Exception $e){
            DB::rollBack();
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //dd($user);
        try{
            $this->validate($request, [
                'name' => 'required|min:2',
                'email' => 'required|email',
                'gender' => 'required',
                'phone_number' => 'required',
            ]);
            $user->update($request->all());

            return $request->wantsJson()
                ? new JsonResponse($user, 200)
                : back()->with('success', 'User Updated Successfully');
        } catch(\Exception $e){
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $user)
    {
        try {
            $user = User::find(Crypt::decrypt($user));
            $user->delete();
            return $request->wantsJson()
                ? new JsonResponse($user, 200)
                : back()->with('success', 'User Deleted Successfully');
        } catch(\Exception $e){
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }


    /**
     * Remove collection of resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(Request $request)
    {
        try {
            $ids = [];
            foreach($request->ids as $id){
                $ids[] = Crypt::decrypt($id);
            }
            User::whereIn('id', $ids)->delete();
            return $request->wantsJson()
                ? new JsonResponse(['message'=> 'Users Deleted Successfully'], 200)
                : back()->with('success', 'User Deleted Successfully');
        } catch(\Exception $e){
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restoreUser(Request $request, $id){
        try{
            User::withTrashed()->find(Crypt::decrypt($id))->restore();
            return $request->wantsJson()
                ? new JsonResponse(['message' => 'User Restored Successfully'], 201)
                : back()->with('success', 'User Restored Successfully');
        } catch(\Exception $e){
            return $request->wantsJson()
                ? new JsonResponse(['message' => $e->getMessage()], 501)
                : back()->with('warning', $e->getMessage());
        }
    }
}
