<template>
    <Bar
        :chartData="dataset"
        :chartOptions="options"
        :height="60"
        :width="300"
    />
</template>

<script>
import { Line } from "vue-chartjs";
import { Bar } from "vue-chartjs";
import charts from "../mixins/charts";
import "chart.js/auto";

export default {
    name: "IndexViewBarChart",
    components: { Line, Bar },

    mixins: [charts],

    props: {
        settings: Object,
        dataset: Object,
    },

    data: function () {
        return {
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 0,
                },
                legend: {
                    display: false,
                },
                title: {
                    display: false,
                },
            },
        };
    },

    computed: {
        chartData: function () {
            return this.createChartDataset();
        },
    },
};
</script>
