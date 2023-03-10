<template>
    <div 
        v-for="(parameter, index) in this.parameters" :key="index"
        class="flex flex-col md:flex-row"
    >
        <div class="md:mt-0 md:px-8 md:py-5 md:w-1/5 mt-2 px-6">
            <label class="inline-block pt-2 leading-tight">
                {{parameter}}
            </label>
        </div>
        <div
            class="mt-1 md:mt-0 pb-5 px-6 md:px-8 md:w-3/5 w-full md:py-5"
        >
            <input
                v-model="value[parameter]"
                type="number"
                :placeholder="parameter"
                class="flex-1 form-control form-input form-input-bordered"
            />
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'value': Object,
            'parameters': Array,
        },
    }
</script>
