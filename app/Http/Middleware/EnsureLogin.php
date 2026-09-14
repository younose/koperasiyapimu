<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;
class EnsureLogin {
  public function handle(Request $request, Closure $next){
    if(!cu()) return redirect()->route('login');
    return $next($request);
  }
}
