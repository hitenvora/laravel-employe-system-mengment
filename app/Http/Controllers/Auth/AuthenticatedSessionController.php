<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\UserLoginTimes;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $loginTime = Carbon::now('Asia/Kolkata');

        UserLoginTimes::create([

            'user_id' => $request->user()->id,
            'last_login_at' => $loginTime,
        ]);
        $request->session()->regenerate();


        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            // There is an authenticated user, so log them out
            Auth::guard('web')->logout();
            $logoutTime = Carbon::now('Asia/Kolkata');
            // Update the user's last_logout_at time
            UserLoginTimes::where('user_id', $user->id)
                ->update(['last_logout_at' => $logoutTime]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }


    public function show()
    {
    }


    // Controller
    public function showtime(Request $request)
    {
        $userLoginTimes = UserLoginTimes::with('name_of_user')
            ->orderBy('id', 'desc')
            ->get();
    
        $totalTimeInSeconds = 0; // Initialize the total time to 0
        foreach ($userLoginTimes as $record) {
            if ($record->last_login_at && $record->last_logout_at) {
                // Calculate the time difference in seconds and add it to the total
                $loginTime = Carbon::parse($record->last_login_at);
                $logoutTime = Carbon::parse($record->last_logout_at);
                $totalTimeInSeconds += $logoutTime->diffInSeconds($loginTime);
            }
        }
    
        // Convert total time to HH:MM:SS format
        $totalTimeFormatted = gmdate('H:i:s', $totalTimeInSeconds);
    
        return view('showalldata', compact('userLoginTimes', 'totalTimeFormatted'));
    }
    





  
}
