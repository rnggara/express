<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\AppSetting;
use App\Models\ConfigCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path('public_html');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot(Request $request)
    {

        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        if (!Collection::hasMacro('sortByMulti')) {
            /**
             * An extension of the {@see Collection::sortBy()} method that allows for sorting against as many different
             * keys. Uses a combination of {@see Collection::sortBy()} and {@see Collection::groupBy()} to achieve this.
             *
             * @param array $keys An associative array that uses the key to sort by (which accepts dot separated values,
             *                    as {@see Collection::sortBy()} would) and the value is the order (either ASC or DESC)
             */
            Collection::macro('sortByMulti', function (array $keys) {
                $currentIndex = 0;
                $keys = array_map(function ($key, $sort) {
                    return ['key' => $key, 'sort' => $sort];
                }, array_keys($keys), $keys);

                $sortBy = function (Collection $collection) use (&$currentIndex, $keys, &$sortBy) {
                    if ($currentIndex >= count($keys)) {
                        return $collection;
                    }

                    $key = $keys[$currentIndex]['key'];
                    $sort = $keys[$currentIndex]['sort'];
                    $sortFunc = $sort === 'DESC' ? 'sortByDesc' : 'sortBy';
                    $currentIndex++;
                    return $collection->$sortFunc($key)->groupBy($key);
                };

                return $sortBy($this);
            });
        }

        $locale = \App::getLocale();
        \App::setLocale("id");

        if(\Config::get('constants.FORCE_HTTPS',false)) { // Default value should be false for local server
            \URL::forceScheme('https');
        }

        Validator::extend('deleted', function ($attribute, $value, $parameters, $validator) {
            $user = User::where("email", $value)->withTrashed()->first();
            // dd($attribute, $value, $parameters, $user);
            // $user = User::where('email', $parameters['email'])
            if(empty($user)){
                return true;
            }
            return $user->deleted_at == null;
        });

        Validator::extend('website', function ($attribute, $value, $parameters, $validator) {
            // validation logic, e.g
            return filter_var($value, FILTER_VALIDATE_URL);
          });

        if (!$this->app->runningInConsole()){

            $lp_branding = \App\Models\Pref_landing_employer::first();
            \Config::set("constants.APP_NAME", $lp_branding->app_name ?? env("APP_NAME"));
            \Config::set("constants.APP_ICON", $lp_branding->favicon ?? env("APP_ICON"));
            \Config::set("constants.APP_LOGO", $lp_branding->logo ?? env("APP_LOGO"));
            \Config::set("constants.COLOR_PRIMARY", $lp_branding->branding_color);
            \Config::set("constants.COLOR_ACCENT", $lp_branding->branding_color_accent);

            $company = ConfigCompany::get();
                view()->share('comp', $company);
                $parent = ConfigCompany::whereNull('id_parent')->orderBy('id')->get();

                if(!empty(Session::get("company_id_parent"))){
                    $comp_parent = $parent->where('id', Session::get("company_id_parent"))->first();
                } else {
                    $comp_parent = $parent->first();
                }

                $bank_source = DB::table("master_banks")->get()->pluck('bank_name', 'bank_code');

                view()->share('master_banks', $bank_source);

                $menu_list = DB::table("config_menu")->where("show_menu", 1)->orderBy("order_menu")->whereNull("deleted_at")->get();

                $menu_view = [];


                foreach($menu_list->whereNotNull("parent")->whereNull("route") as $item){
                    $item->links = $menu_list->where("parent", $item->id)->toArray();
                }

                foreach($menu_list->whereNull("parent")->whereNull("route") as $item){
                    $item->sub_headers = $menu_list->where("parent", $item->id)->whereNull("route")->toArray();
                    $item->links = $menu_list->where("parent", $item->id)->whereNotNull("route")->toArray();
                    $menu_view[] = $item;
                }

                $path = \Request::path();
                if (strpos($path, '/') !== false) {
                    $path_ar = explode('/',$path);
                    $title = '';
                    $title = ucwords(str_replace('-',' ',$path_ar[1]));
                } else {
                    $title = ucwords(str_replace('-',' ',$path));
                }

                if (strlen($title) <= 3){
                    $title = strtoupper($title);
                }

                view()->share("title_page", $title);
                view()->share('menu_view', $menu_view);
                view()->share("menu_mobile", DB::table("config_menu")->where("show_menu", 1)->whereNotNull("parent")->whereNotNull("route")->whereNull("deleted_at")->get());

                $app_setting = AppSetting::where('id', 1)->first();
                view()->share('app_comp', $comp_parent);
                view()->share('login_logo', $app_setting->login_logo);
                view()->share('dashboard_logo', $app_setting->dashboard_logo);
                view()->share('footer_tag', $app_setting->footer_tag);


            $uom = array("kg", "unit", "buah", "meter", "pack", "roll", "ea", "buku", "inch", "lusin", "set", "rim", "gallon", "feet", "litre", "can", "lbs", "joint", "box", "bottle", "gram", "lembar", "drum", "lot", "day", "lumpsum", "monthly", "kali");
            view()->share('uom', $uom);
            $list_currency = '{"AUD": "Australian Dollar", "CNY": "Chinese Yuan", "EUR": "Euro", "GBP": "British Pound Sterling", "IDR": "Indonesian Rupiah", "JPY": "Japanese Yen", "SGD": "Singapore Dollar", "USD": "United States Dollar"}';
            view()->share('list_currency', $list_currency);
            $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
            view()->share('mnth_array', $array_bln);
            $days = [
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
                'Sun'
            ];
            $id_month = [1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $id_month_sort = [1=>"Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            $hariId = [1=>"Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
            $daySort = [1=>"Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
            $dayFull = [1=>"Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
            view()->share("idFullMonth", $id_month);
            view()->share("idShortMonth", $id_month_sort);
            view()->share('days_array', $days);
            view()->share('hariId', $hariId);
            view()->share('daySort', $daySort);
            view()->share('dayFull', $dayFull);

            $service_group = \App\Models\Express_vendor::whereNotNull("website")
                ->get()->pluck("website", "group_vendor");

            view()->share("service_group", $service_group);

            $locale = $request->cookie("web_locale") ?? "id";
            // dd($locale);

            App::setLocale($locale);

            view()->share("app_locale", $locale);

            $file_env = app_path('Config/.env');
            $env = explode("\n", file_get_contents($file_env));
            for ($i=0; $i < count($env); $i++){
                $content_env = explode("=", $env[$i]);
                if ($content_env[0] == "ACCOUNTING_MODE"){
                    $accounting_mode = end($content_env);
                }
                if ($content_env[0] == "DEBUG"){
                    $debug = end($content_env);
                }
            }
            $companies = array();
            foreach ($company as $item) {
                $companies[$item->id] = $item;
            }

            view()->share('accounting_mode', $accounting_mode);
            view()->share('debug', $debug);
            view()->share('view_company', $companies);

            config(['app.locale' => 'id']);
            Carbon::setLocale('id');
            date_default_timezone_set('Asia/Jakarta');
        }
    }
}
