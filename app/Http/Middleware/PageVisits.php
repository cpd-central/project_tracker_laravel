<?php

namespace App\Http\Middleware;

use Closure;
use \Auth;
use App\Page;

class PageVisits
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
        $uri = $request->path();
        $user = Auth::user();

        $page = Page::Where('name', $uri)->get();
        if($page->isEmpty()){
            $page = new Page();
            $page->name = $uri;
            $visitors = [];
            $visitors[now()->timestamp] = $user['name'];
            $page->visitors = $visitors;
            $page->counter = 1;
        }
        else{
            $page = $page[0];
            $page->counter += 1;
            $visitors = $page->visitors;
            $visitors[now()->timestamp] = $user['name'];
            $page->visitors = $visitors;
        }
        $page->save();
        return $next($request);
    }
}
