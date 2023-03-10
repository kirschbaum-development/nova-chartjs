<template>
    <Bar
        :chartData="chartData"
        :chartOptions="settings.options"
        :width="settings.width"
        :height="settings.height"
    />
</template>

<script>
import { Bar } from "vue-chartjs";
import "chart.js/auto";
import charts from "../mixins/charts";

export default {
    name: "ChartjsBarChart",
    components: { Bar },
    mixins: [charts],

    props: {
        dataset: Array,
        additionalDatasets: Array,
        settings: Object,
    },

    computed: {
        chartData() {
            return this.createChartDataset();
        },
    },
};
</script>
