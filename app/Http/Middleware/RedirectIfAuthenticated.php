<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class RedirectIfAuthenticated
// {
//     public function handle(Request $request, Closure $next)
//     {
//         if (Auth::check()) {
//             $user = Auth::user();

//             if ($user->role->name === 'admin_icodsa') {
//                 return redirect('/dashboard/icodsa');
//             }

//             if ($user->role->name === 'admin_icicyta') {
//                 return redirect('/dashboard/icicyta');
//             }

//             if ($user->role->name === 'superadmin') {
//                 return redirect('/dashboard/superadmin');
//             }
//         }

//         return $next($request);
//     }
// }
