<template>
    <div>
        <div class="flex border-b border-40">
            <div v-show="field.showLabel" class="w-1/4 py-4">
                <h4 class="font-normal text-80">{{ field.name }}</h4>
            </div>
            <div class="w-3/4 py-4 flex-grow">
                <div class="flex flex-col md:flex-row">
                    <div class="md:mt-0 md:px-8 md:py-5 md:w-2/5 mt-2 px-6">
                        <h4 class="inline-block pt-2 leading-tight">
                            Select another {{ field.chartableName }} to compare
                        </h4>
                    </div>
                    <div
                        class="mt-1 md:mt-0 pb-5 px-6 md:px-8 md:w-3/5 w-full md:py-5"
                    >
                        <multiselect
                            v-model="selected"
                            :multiple="true"
                            :searchable="true"
                            group-values="groupItems"
                            group-label="groupLabel"
                            placeholder="Add items for comparison"
                            :group-select="true"
                            :label="field.settings.titleProp"
                            track-by="id"
                            :options="comparisonList"
                            :loading="isLoading"
                            @search-change="getComparisonData"
                        />
                    </div>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-full py-4" v-if="loaded">
                        <component
                            :is="guessChartType()"
                            :dataset="comparisonDataset"
                            :additionalDatasets="additionalDatasets"
                            :settings="field.settings"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import colors from "../mixins/colors";
import datasetHandler from "../mixins/datasetHandler";
import ChartjsLineChart from "./ChartjsLineChart";
import ChartjsBarChart from "./ChartjsBarChart";

export default {
    components: {
        Multiselect,
        ChartjsLineChart,
        ChartjsBarChart,
    },

    mixins: [colors, datasetHandler],

    props: ["resource", "resourceName", "resourceId", "field"],

    data() {
        return {
            isLoading: false,
            loaded: false,
            selected: [],
            comparisonList: [
                {
                    groupLabel: "Select/Deselect All",
                    groupItems: [],
                },
            ],
            additionalDatasets: [],
        };
    },

    created() {
        this.getAdditionalDatasets();
    },

    methods: {
        isType: function (type) {
            return this.field.settings.type.toLowerCase() === type;
        },

        isNotUser: function (element, index, array) {
            return element[this.field.settings.identProp] != this.field.ident;
        },

        getDatapoint: function (values, title, color) {
            if (!color) {
                color = this.getRandomColor();
            }

            if (!values || typeof values != "object") {
                values = [];
            }

            return {
                label: title,
                data: this.getAllowedParametersFromDataset(
                    this.field.settings.parameters,
                    values
                ),
                ...this.getChartTypeCustomizations(
                    this.field.settings.type,
                    color
                ),
            };
        },

        getChartTypeCustomizations: function (type, color) {
            if (this.isType("line")) {
                return {
                    borderColor: color,
                };
            } else {
                return {
                    backgroundColor: color,
                };
            }
        },

        getComparisonData: async function (searchValue) {
            let response = await Nova.request().post(
                "/nova-chartjs/retrieve-model-comparison-data",
                {
                    field: this.field,
                    searchFields: this.field.searchFields,
                    searchValue: searchValue,
                }
            );

            this.comparisonList = [
                {
                    groupLabel: "Select/Deselect All",
                    groupItems: response.data.comparison,
                },
            ];
        },

        getAdditionalDatasets: async function () {
            this.isLoading = true;

            let response = await Nova.request().post(
                "/nova-chartjs/get-additional-datasets",
                this.field
            );

            this.isLoading = false;
            this.additionalDatasets = response.data.additionalDatasets;
            this.loaded = true;
        },

        guessChartType: function () {
            switch (this.field.settings.type.toLowerCase()) {
                case "line":
                    return "ChartjsLineChart";
                case "bar":
                    return "ChartjsBarChart";
            }
        },
    },

    computed: {
        comparisonDataset: function () {
            let chartData = [];
            if (
                !this.field.notEditable ||
                Object.keys(this.field.value).length
            ) {
                chartData.push(
                    this.getDatapoint(
                        this.field.value,
                        this.field.title,
                        this.field.settings.color
                    )
                );
            }

            return [
                ...chartData,
                ...this.selected.map((data) =>
                    this.getDatapoint(
                        data.novaChartjsComparisonData,
                        data[this.field.settings.titleProp]
                    )
                ),
            ];
        },
    },
};
</script>
