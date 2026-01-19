<?php

namespace App\Providers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RolesManagementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('moduleStart', function($expression){
            return "<?php if(RolesManagement::moduleStart({$expression})) : ?>";
        });

        \Blade::directive('moduleEnd', function(){
            return "<?php endif; ?>";
        });

        \Blade::directive('hasAction', function($expression){
            return "<?php print_r(RolesManagement::hasAction({$expression})); ?>" ;
        });

        \Blade::directive('actionStart', function($expression){
            $eE = explode(',', preg_replace("/[\(\)]/", '', $expression), 2);
            return "<?php if(RolesManagement::actionStart($eE[0], $eE[1])) : ;?>";
        });

        \Blade::directive('actionElse', function(){
            return "<?php else: ?>";
        });

        \Blade::directive('actionEnd', function(){
            return "<?php endif; ?>";
        });

        \Blade::directive('hasPermission', function($expression){
            $eE = explode(',', preg_replace("/[\(\)]/", '', $expression), 2);
            $v1 = $eE[0];
            $v2 = $eE[1] ?? null;
            $v3 = $eE[2] ?? null;
            return "<?php if(RolesManagement::hasPermission($v1, $v2, $v3)) : ?>";
        });

        \Blade::directive('endPermission', function(){
            return "<?php endif; ?>";
        });

        \Blade::directive("dateId", function($value){
            $script = '<?php
            $id_month = [1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $d = date("d", strtotime('.$value.'));
            $m = date("n", strtotime('.$value.'));
            $y = date("Y", strtotime('.$value.'));
            $F = $id_month[$m];
            $date = "$d $F $y";
            if(empty('.$value.')){
                $date = "-";
            }
            echo $date;
            ?>';

            return $script;
        });

        \Blade::directive("dayId", function($value){
            $script = '<?php
            $id_day = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum\'at", "Sabtu"];
            $d = date("w", strtotime('.$value.'));
            $date = $id_day[$d];
            if(empty('.$value.')){
                $date = "-";
            }
            echo $id_day[$d];
            ?>';

            return $script;
        });

        \Blade::directive("monthId", function($value){
            $script = '<?php
            $id_month = [1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $d = date("n", strtotime('.$value.'));
            $date = $id_month[$d];
            if(empty('.$value.')){
                $date = "-";
            }
            echo $id_month[$d];
            ?>';

            return $script;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('RolesManagement', function()
        {
            return new \App\Rms\RolesManagement;
        });
    }
}
