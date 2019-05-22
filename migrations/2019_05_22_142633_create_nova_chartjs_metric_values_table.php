<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNovaChartJsMetricValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nova_chartjs_metric_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('chartable');
            $table->json('metric_values')->nullable();
            $table->timestamps();

            $table->unique(['chartable_type', 'chartable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nova_chart_js_metric_values');
    }
}
