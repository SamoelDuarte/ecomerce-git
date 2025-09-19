<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use App;

class SetLangMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    // Skip database queries during console commands
    if (app()->runningInConsole()) {
      return $next($request);
    }

    if (session()->has('lang')) {
      app()->setLocale(session()->get('lang'));
    } else {
      $defaultLang = Language::where('is_default', 1)->first();
      if (!empty($defaultLang)) {
        app()->setLocale($defaultLang->code);
      } else {
        // Fallback para o idioma configurado no .env se não encontrar no banco
        app()->setLocale(config('app.locale', 'pt-br'));
      }
    }


    return $next($request);
  }
}
