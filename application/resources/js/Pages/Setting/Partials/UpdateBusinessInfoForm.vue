<script setup>
import {useForm, usePage} from '@inertiajs/vue3';
import {computed, ref} from "vue";

const props = defineProps({
    supportedCurrencies: Object,
});

const page = usePage();
const showSuccessMessage = ref(false);

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
        onSuccess: () => {
            showSuccessMessage.value = true;
        }
    });
};
</script>

<template>
    <v-card class="mb-6">
        <v-card-title>Business Information</v-card-title>
        <v-card-subtitle>
            Tell us about your business, such as what currency you want to
            charge your customers in, your business name and terms and
            conditions.
        </v-card-subtitle>

        <v-card-text>
            <v-form @submit.prevent="updateBusinessInfo">
                <v-select 
                    label="Account Currency"
                    v-model="form.account_currency"
                    :items="currencies"
                    :error-messages="form.errors.account_currency"
                ></v-select>
                
                <v-text-field 
                    v-model="form.business_name" 
                    type="text"
                    autocomplete="business-name" 
                    label="Business Name"
                    :error-messages="form.errors.business_name"
                ></v-text-field>
                
                <v-textarea 
                    v-model="form.business_terms"
                    label="Business Terms & Conditions"
                    :error-messages="form.errors.business_terms"
                ></v-textarea>

                <div class="d-flex justify-end mt-4">
                    <v-snackbar
                        v-model="showSuccessMessage"
                        color="success"
                        timeout="3000"
                    >
                        Business information updated successfully!
                    </v-snackbar>
                    
                    <v-btn
                        color="primary"
                        :loading="form.processing"
                        type="submit"
                    >
                        Save
                    </v-btn>
                </div>
            </v-form>
        </v-card-text>
    </v-card>
</template>
