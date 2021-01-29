<script>
    import { Radar, mixins } from 'vue-chartjs'
    import charts from "../mixins/charts";

    const {reactiveData} = mixins;

    export default {
        extends: Radar,

        mixins: [reactiveData, charts],
    }
</script>
