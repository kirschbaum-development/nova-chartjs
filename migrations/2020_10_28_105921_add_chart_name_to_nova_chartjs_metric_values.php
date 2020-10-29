<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChartNameToNovaChartjsMetricValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nova_chartjs_metric_values', function (Blueprint $table) {
            $table->string('chart_name', 100)->after('metric_values')->default('default');
            
            $table->dropUnique('nova_chartjs_metric_values_chartable_type_chartable_id_unique');
            $table->unique(['chartable_type', 'chartable_id', 'chart_name'], 'nova_chartjs_metric_values_chart_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nova_chartjs_metric_values', function (Blueprint $table) {
            $table->dropColumn('chart_name');

            $table->dropUnique('nova_chartjs_metric_values_chart_unique');
            $table->unique(['chartable_type', 'chartable_id']);
        });
    }
}
