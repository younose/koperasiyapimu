<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;
class EnsureAdmin {
  public function handle(Request $request, Closure $next){
    if(!cu()) return redirect()->route('login');
    if(!is_admin()) return redirect()->route('portal');
    return $next($request);
  }
}
