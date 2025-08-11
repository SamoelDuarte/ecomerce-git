<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Artisan;
use Session;

class CacheController extends Controller
{
    public function clear() {
      try {
          \Log::info('Iniciando limpeza de cache...');

          $cacheResult = Artisan::call('cache:clear');
          \Log::info('cache:clear resultado: ' . $cacheResult);

          $configResult = Artisan::call('config:clear');
          \Log::info('config:clear resultado: ' . $configResult);

          $routeResult = Artisan::call('route:clear');
          \Log::info('route:clear resultado: ' . $routeResult);

          $viewResult = Artisan::call('view:clear');
          \Log::info('view:clear resultado: ' . $viewResult);

          // Adiciona limpeza do optimize
          $optimizeResult = Artisan::call('optimize:clear');
          \Log::info('optimize:clear resultado: ' . $optimizeResult);

          // Pega a saída dos comandos
          $output = Artisan::output();
          \Log::info('Saída dos comandos: ' . $output);

          Session::flash('success', __('Cache, route, view, config cleared successfully'));
          return back();

      } catch (\Exception $e) {
          \Log::error('Erro ao limpar cache: ' . $e->getMessage());
          Session::flash('error', 'Erro ao limpar cache: ' . $e->getMessage());
          return back();
      }
    }
}
