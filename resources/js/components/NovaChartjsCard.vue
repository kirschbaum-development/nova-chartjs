<template>
    <card class="p-10 nova-chartjs-card">
      <div class="w-full py-4">
            <chartjs-bar-chart v-if="isType('bar')"
                :dataset="comparisonDataset"
                :additionalDatasets="card.additionalDatasets"
                :settings="card.settings"
                :height="card.settings.height"
                :width="card.settings.width"
            />
            <chartjs-line-chart v-else
                :dataset="comparisonDataset"
                :additionalDatasets="card.additionalDatasets"
                :settings="card.settings"
                :height="card.settings.height"
                :width="card.settings.width"
            />
        </div>
    </card>
</template>

<script>
import ChartjsLineChart from "./ChartjsLineChart";
import ChartjsBarChart from "./ChartjsBarChart";
import colors from "../mixins/colors";
import datasetHandler from "../mixins/datasetHandler";
export default {
    components: {
        ChartjsLineChart,
        ChartjsBarChart
    },

    mixins: [colors, datasetHandler],

    props: ['card'],

    data() {
        return {
            selected: []
        }
    },

    methods: {
        isType: function(type){
            return this.card.settings.type.toLowerCase() === type
        },

        getDatapoint: function(values, title, color){
            if(!color){
                color = this.getRandomColor();
            }

            if(!values || (typeof values != 'object')){
                values = [];
            }

            return {
                label: title,
                data: this.getAllowedParametersFromDataset(this.card.settings.parameters, values),
                ...this.getChartTypeCustomizations(this.card.settings.type, color)
            }
        },

        getChartTypeCustomizations: function(type, color){
            if(this.isType('line')){
                return {
                    borderColor: color
                }
            }else{
                return {
                    backgroundColor: color
                }
            }
        }
    },

    computed: {
        comparisonDataset: function(){
            return [
                ...this.card.dataset.map(
                    data => this.getDatapoint(
                        data.novaChartjsComparisonData,
                        data[this.card.settings.titleProp]
                    )
                )
            ];
        },
    }

}
</script>
<style lang="scss" scoped>
.nova-chartjs-card {
    height: auto !important;
    min-height: 150px;
}
</style>