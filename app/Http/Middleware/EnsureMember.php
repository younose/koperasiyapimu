<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;
class EnsureMember {
  public function handle(Request $request, Closure $next){
    if(!cu()) return redirect()->route('login');
    if(!is_member()) return redirect()->route('dashboard');
    return $next($request);
  }
}
