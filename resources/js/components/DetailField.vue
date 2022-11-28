<template>
    <div>
        <div class="flex border-b border-40">
            <div v-show="field.showLabel" class="w-1/4 py-4">
                <h4 class="font-normal text-80">{{ field.name }}</h4>
            </div>
            <div class="w-3/4 py-4 flex-grow">
                <div class="flex border-b border-40">
                    <div class="w-1/4 py-4"><h4 class="font-normal text-80">Select another {{field.chartableName}} to compare</h4></div>
                    <div class="w-3/4 py-4">
                        <multiselect
                            v-model = "selected"
                            :multiple = "true"
                            :searchable = "true"
                            group-values="groupItems"
                            group-label="groupLabel"
                            placeholder="Add items for comparison"
                            :group-select = "true"
                            :label="field.settings.titleProp"
                            track-by="id"
                            :options = "comparisonList"
                            :loading="isLoading"
                            @search-change="getComparisonData"
                        />
                    </div>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-full py-4" v-if="loaded">
                        <chartjs-bar-chart v-if="isType('bar')"
                           :dataset="comparisonDataset"
                           :additionalDatasets="additionalDatasets"
                           :settings="field.settings"
                           :height="field.settings.height"
                           :width="field.settings.width"
                        />
                        <chartjs-line-chart v-else
                            :dataset="comparisonDataset"
                            :additionalDatasets="additionalDatasets"
                            :settings="field.settings"
                            :height="field.settings.height"
                            :width="field.settings.width"
                        />

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ChartjsLineChart from "./ChartjsLineChart";
import ChartjsBarChart from "./ChartjsBarChart";
import Multiselect from 'vue-multiselect';
import colors from "../mixins/colors";
import datasetHandler from "../mixins/datasetHandler";

export default {
    components: {
        Multiselect,
        ChartjsLineChart,
        ChartjsBarChart
    },

    mixins: [colors, datasetHandler],

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            isLoading: false,
            loaded: false,
            selected: [],
            comparisonList: [{
                groupLabel: 'Select/Deselect All',
                groupItems: []
            }],
            additionalDatasets: []
        }
    },

    created() {
        this.getAdditionalDatasets()
    },

    methods: {
        isType: function(type){
            return this.field.settings.type.toLowerCase() === type
        },

        isNotUser: function(element, index, array){
            return element[this.field.settings.identProp] != this.field.ident;
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
                data: this.getAllowedParametersFromDataset(this.field.settings.parameters, values),
                ...this.getChartTypeCustomizations(this.field.settings.type, color)
            }
        },

        getChartTypeCustomizations: function(type, color) {
            if(this.isType('line')){
                return {
                    borderColor: color
                }
            }else{
                return {
                    backgroundColor: color
                }
            }
        },

        getComparisonData: async function(searchValue){
            let response = await Nova.request()
                .post("/nova-chartjs/retrieve-model-comparison-data",
                    {
                        "field": this.field,
                        "searchFields": this.field.searchFields,
                        "searchValue": searchValue
                    }
                )

            this.comparisonList = [
                {
                    groupLabel: 'Select/Deselect All',
                    groupItems: response.data.comparison
                },
            ]
        },

        getAdditionalDatasets: async function() {
            this.isLoading = true

            let response = await Nova.request()
                .post("/nova-chartjs/get-additional-datasets", this.field)

            this.isLoading = false
            this.additionalDatasets = response.data.additionalDatasets;
            this.loaded = true;
        },
    },

    computed: {
        comparisonDataset: function(){
            let chartData = [];
            if(! this.field.notEditable || Object.keys(this.field.value).length){
                chartData.push(this.getDatapoint(this.field.value, this.field.title, this.field.settings.color));
            }

            return [
                ...chartData,
                ...this.selected.map(
                    data => this.getDatapoint(
                        data.novaChartjsComparisonData,
                        data[this.field.settings.titleProp]
                    )
                )
            ];
        },

        valueDataset: function () {
            return {
                labels: Object.keys(this.field.value),
                datasets: Object.values(this.field.value)
            }
        },
    }
}
</script>
