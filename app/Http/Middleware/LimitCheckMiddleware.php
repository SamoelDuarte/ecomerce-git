<?php

namespace App\Http\Middleware;

use App\Http\Helpers\LimitCheck;
use App\Http\Helpers\UserPermissionHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LimitCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $feature = null, $method = null, $type = null)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $permissions =  UserPermissionHelper::currentPackagePermission($user->id);
            $downgradeText = __('Your feature limit is over or downgraded!');
            $featuresCount = LimitCheck::packageFeaturesCount($user->id);
            if ($method == 'store') {
                //for items
                if ($feature == 'items') {
                    if ($permissions->product_limit > $featuresCount['items'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for categories
                if ($feature == 'categories') {
                    if ($permissions->categories_limit > $featuresCount['categories'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for subcategories
                if ($feature == 'subcategories') {
                    if ($permissions->subcategories_limit > $featuresCount['subcategories'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for languages
                if ($feature == 'languages') {
                    if ($permissions->language_limit > $featuresCount['languages'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for custome_page
                if ($feature == 'custome_page') {
                    if ($permissions->number_of_custom_page > $featuresCount['custome_page'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for blogs
                if ($feature == 'blogs') {
                    if ($permissions->post_limit > $featuresCount['blogs'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
            }
            if ($method == 'update') {
                //for items
                if ($feature == 'items') {
                    if ($permissions->product_limit >= $featuresCount['items'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for categories
                if ($feature == 'categories') {
                    if ($permissions->categories_limit >= $featuresCount['categories'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for subcategories
                if ($feature == 'subcategories') {
                    if ($permissions->subcategories_limit >= $featuresCount['subcategories'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for languages
                if ($feature == 'languages') {
                    if ($permissions->language_limit >= $featuresCount['languages'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for custome_page
                if ($feature == 'custome_page') {
                    if ($permissions->number_of_custom_page >= $featuresCount['custome_page'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
                //for blogs
                if ($feature == 'blogs') {
                    if ($permissions->post_limit >= $featuresCount['blogs'] && $this->checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)) {
                        return $next($request);
                    } else {
                        if ($type == 'without_ajax') {
                            session()->put('modal-show', true);
                            return redirect()->back()->with('warning', $downgradeText);
                        } else {
                            return response()->json('downgrade');
                        }
                    }
                }
            }
        }
    }

    private function checkFeaturesNotDowngraded($feature, $permissions, $featuresCount)
    {
        $response = true;
        if ($feature != 'items') {
            if ($permissions->product_limit < $featuresCount['items']) {
                return  $response = false;
            }
        }
        if ($feature != 'categories') {
            if ($permissions->categories_limit < $featuresCount['categories']) {
                return  $response = false;
            }
        }
        if ($feature != 'subcategories') {
            if ($permissions->subcategories_limit < $featuresCount['subcategories']) {
                return  $response = false;
            }
        }
        if ($feature != 'languages') {
            if ($permissions->language_limit < $featuresCount['languages']) {
                return  $response = false;
            }
        }
        if ($feature != 'custome_page') {
            if ($permissions->number_of_custom_page < $featuresCount['custome_page']) {
                return  $response = false;
            }
        }
        if ($feature != 'blogs') {
            if ($permissions->post_limit < $featuresCount['blogs']) {
                return  $response = false;
            }
        }
        return $response;
    }
}
