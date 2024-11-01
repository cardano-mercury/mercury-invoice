<script setup>
import {useForm, usePage} from '@inertiajs/vue3';
import FormSection from "@/Components/FormSection.vue";
import {computed} from "vue";

const props = defineProps({
    supportedCurrencies: Object,
});

const page = usePage();

const currencies = computed(() => {
    const val = [];
    for (const [key, value] of Object.entries(props.supportedCurrencies)) {
        val.push({
            title: value,
            value: key
        });
    }
    return val;
})

const form = useForm({
    account_currency: page.props.auth.user.account_currency,
    business_name: page.props.auth.user.business_name,
    business_terms: page.props.auth.user.business_terms,
});

const updateBusinessInfo = () => {
    form.post(route('user.settings.save-business-info'), {
        errorBag: 'updateBusinessInfo',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateBusinessInfo">
        <template #title>
            <h2>Update Business Info</h2>
        </template>

        <template #description>
            Tell us about your business, such as what currency you want to
            charge your customers in, your business name and terms and
            conditions.
        </template>

        <template #form>
            <v-select label="Account Currency"
                      v-model="form.account_currency"
                      :items="currencies"></v-select>
            <v-text-field v-model="form.business_name" type="text"
                          autocomplete="business-name" label="Business Name"/>
            <v-textarea v-model="form.business_terms"
                        label="Business Terms & Conditions"/>
        </template>

        <template #actions>
            <v-btn color="primary" :disabled="form.processing" type="submit">
                Save
            </v-btn>
        </template>
    </FormSection>
</template>
