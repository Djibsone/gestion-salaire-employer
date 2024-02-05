<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\ValidateAccountRequest;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Notifications\SendToAdminAfterRegistrationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function index ()
    {
        $admins = User::paginate(5);
        return view('admins.index', [
            'admins' => $admins
        ]);
    }
 
    public function create ()
    {
        return view('admins.create');
    }

    public function edit (User $user)
    {
        return view('admins.edit', [
            'user' => $user,
        ]);
    }

    public function store (AdminRequest $request)
    {
        $user = User::create($request->validated());

        // Envoyer un code pour verification
        if ($user) {
            ResetCodePassword::where('email', $user->email)->delete();
            $code = rand(1000, 4000);

            $data = [
                'code' => $code,
                'eamil' => $user->email
            ];

            ResetCodePassword::create($data);

            // Envoyer la notification à l'utilisateur
            Notification::route('mail', $user->email)->notify(new SendToAdminAfterRegistrationNotification($code, $user->email));
        }        

        return to_route('administrateur.index')->with('success_message', 'L\'admin enregistré.');
    }

    public function update (AdminRequest $request, User $user)
    {
        $user->update($request->validated());
        return to_route('administrateur.index')->with('success_message', 'L\'admin mis à jours.');

    }

    public function delete (User $user)
    {
        $connetedAdminId = Auth::user()->id;

        if ($connetedAdminId !== $user->id) {
            $user->delete();
            return to_route('administrateur.index')->with('success_message', 'L\'admin supprimé.');
        }
        else {
            return to_route('administrateur.index')->with('error_message', 'Vous ne pouvez pas supprimer votre compte administrateur.');
        }

    }

    public function defineAccess ($email)
    {
        $checkeUserExist = User::where('email', $email)->first();

        if ($checkeUserExist) {
            return view('auth.validate-account');
        } else {
            # code...
        }
        
    }

    public function submitDefineAccess (ValidateAccountRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->email_verified_at = Carbon::now();
            $user->update();

            if ($user) {
                $existingCode = ResetCodePassword::where('email', $user->email)->count();
           
                if ($existingCode >= 1) {
                    ResetCodePassword::where('email', $user->email)->delete();
                }
            }

            return to_route('login')->with('success_message', 'Vos accès ont été correctement définis.');
        }
        else
        {
            // 404
        }
    }
}
