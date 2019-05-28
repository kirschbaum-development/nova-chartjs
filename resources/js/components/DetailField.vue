<template>
    <div>
        <div class="flex border-b border-40">
            <div class="w-full py-4">
                <multiselect
                    v-model = "selected"
                    :multiple = "true"
                    :searchable = "true"
                    group-values="groupItems"
                    group-label="groupLabel"
                    :group-select = "true"
                    :label="field.settings.titleProp"
                    track-by="id"
                    :options = "comparisonList"
                />
            </div>
        </div>
        <div class="flex border-b border-40">
            <div class="w-full h-screen py-4">
                <chartjs-range-chart
                    :dataset="comparisonDataset"
                    :settings="field.settings"
                />
            </div>
        </div>
    </div>
</template>

<script>
import ChartjsRangeChart from "./ChartjsRangeChart";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect,
        ChartjsRangeChart
    },

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            selected: []
        }
    },

    methods: {
        getRandomColor: function() {
            return "#"+((1<<24)*Math.random()|0).toString(16);
        },

        getAllowedParametersFromDataset: function(parameters, dataset = []) {
            return parameters.map(key => dataset[key] || 0);
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
                label: `${this.field.model}: ${title}`,
                borderColor: color,
                data: this.getAllowedParametersFromDataset(this.field.settings.parameters, values)
            }
        }
    },

    computed: {
        comparisonDataset: function(){
            return [this.getDatapoint(this.field.value, this.field.title, this.field.settings.color), ...this.selected.map(data => this.getDatapoint(data.nova_chartjs_metric_value.metric_values, data[this.field.settings.titleProp]))];
        },

        comparisonList: function(){
            return [
                {
                    groupLabel: 'Select/Deselect All',
                    groupItems: this.field.comparison.filter(this.isNotUser)
                },
            ];
        }
    }
}
</script>
