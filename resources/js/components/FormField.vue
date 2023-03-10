<template slot="field">
    <parameter-editor
        :parameters="field.settings.parameters"
        :value="value"
        :field="field"
    />
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import ParameterEditor from "./ParameterEditor";

export default {
    components: { ParameterEditor },

    mixins: [FormField, HandlesValidationErrors],

    props: ["resourceName", "resourceId", "field"],

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value =
                this.field.value &&
                typeof this.field.value === "object" &&
                this.field.value.constructor === Object
                    ? this.field.value
                    : {};
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            const attributeName =
                this.field.attribute == "metric_values"
                    ? this.field.attribute
                    : `${this.field.attribute}_${
                          this.field.chartName || "default"
                      }`;
            formData.append(attributeName, JSON.stringify(this.value) || "{}");
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value;
        },
    },
};
</script>
