<template>
    <panel-item :field="field">
        <template slot="value">
            <chartjs-range-chart
                :dataset="dataset"
                :settings="field.settings"
            />
        </template>
    </panel-item>
</template>

<script>
import ChartjsRangeChart from "./ChartjsRangeChart";

export default {
    components: {
        ChartjsRangeChart
    },

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            dataset: [
                {
                    label: `${this.field.label} ${this.resourceId}`,
                    borderColor: this.field.settings.color||this.getRandomColor(),
                    data: this.getAllowedParametersFromDataset(this.field.settings.parameters, this.field.value.metric_values)
                }
            ]
        }
    },

    methods: {
        getRandomColor: function() {
            return "#"+((1<<24)*Math.random()|0).toString(16);
        },

        getAllowedParametersFromDataset: function(parameters, dataset = []) {
            return parameters.map(key => dataset[key] || 0);
        }
    }
}
</script>
