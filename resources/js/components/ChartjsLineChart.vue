<script>
    import { Line, mixins } from 'vue-chartjs'
    import charts from "../mixins/charts";

    const {reactiveData} = mixins;

    export default {
        extends: Line,

        mixins: [reactiveData, charts],
    }
</script>
