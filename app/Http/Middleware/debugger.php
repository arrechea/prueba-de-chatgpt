<?php
// File location :: \app\Http\Middleware\debugger.php
namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Closure;
class debugger {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

       Log::debug('Request Begin');

       Log::info('Request Info:'.$request);
       Log::debug($request);

       Log::debug('Request END');
//        DB::connection()->enableQueryLog();
        return $next($request);
    }
    public function terminate($request, $response) {
//        Log::debug('QUERIES__LOG_STARTED');
//        $queries = DB::getQueryLog();
////        $formattedQueries = [];
//        foreach ($queries as $query) :
//            $prep = $query['query'];
//            foreach ($query['bindings'] as $binding) :
//                $prep = preg_replace("#\?#", $binding, $prep, 1);
//            endforeach;
//            Log::debug('Query--->  ' . $prep);
////            $formattedQueries[] = $prep;
//        endforeach;
////        return $formattedQueries;
//        Log::debug('QUERIES__LOG_ENDED');
//
//        Log::info('-----------------------');

        Log::debug('===========Request Begin===========');
//
        Log::info('===========Request Info:'.$request);
        Log::debug($request);
//        Log::debug('===========Request Time:'.(microtime(true) - LARAVEL_START));

        Log::debug('===========Request END===========');
//        DB::connection()->enableQueryLog();
    }
}
