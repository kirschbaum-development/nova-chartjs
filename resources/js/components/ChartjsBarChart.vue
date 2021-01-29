<script>
    import { Bar, mixins } from 'vue-chartjs'
    import charts from "../mixins/charts";

    const {reactiveData} = mixins;

    export default {
        extends: Bar,

        mixins: [reactiveData, charts],
    }
</script>
