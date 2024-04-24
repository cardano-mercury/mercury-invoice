<script setup>
import { ref } from 'vue';
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormSection from "@/Components/FormSection.vue";
import DialogModal from "@/Components/DialogModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/DangerButton.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";

const props = defineProps({
    webhooks: Array,
    hmacAlgorithms: Array,
    eventTargetNames: Array,
});

const createWebhookForm = useForm({
    url: '',
    hmac_algorithm: 'sha256',
    max_attempts: 10,
    timeout_seconds: 15,
    retry_seconds: 30,
    target_events: [],
});

const updateWebhookForm = useForm({
    target_events: [],
});

const deleteWebhookForm = useForm({});

const displayingSecret = ref(false);
const managingTargetEventsFor = ref(null);
const webhookBeingDeleted = ref(null);

const createWebhook = () => {
    createWebhookForm.post(route('webhooks.store'), {
        preserveScroll: true,
        onSuccess: () => {
            displayingSecret.value = true;
            createWebhookForm.reset();
        },
        onError: (e) => {
            console.log('error creating webhook', e);
        }
    });
};

const manageWebhookEventTargets = (webhook) => {
    updateWebhookForm.target_events = []; // TODO
    managingTargetEventsFor.value = webhook;
};

const updateWebhook = () => { }; // TODO

const confirmWebhookDeletion = (webhook) => {
    webhookBeingDeleted.value = webhook;
};

const deleteWebhook = () => {
    deleteWebhookForm.delete(route('webhooks.destroy', webhookBeingDeleted.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (webhookBeingDeleted.value = null),
    });
};

</script>

<template>
    <div>

        <!-- Register New Webhook -->
        <FormSection @submitted="createWebhook">

            <template #title>
                Register New Webhook
            </template>

            <template #description>
                Register your own webhook urls to get notified, when interested events occurs in your account.
            </template>

            <template #form>

                <!-- Webhook URL -->
                <div class="col-span-12">
                    <InputLabel for="url" value="Webhook URL" />
                    <TextInput
                        id="url"
                        placeholder="e.g. https://your-website.com/webhooks/handler"
                        v-model="createWebhookForm.url"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                        required
                    />
                    <InputError :message="createWebhookForm.errors.url" class="mt-2" />
                </div>

                <!-- HMAC  Signature Algorithm -->
                <div class="col-span-6">
                    <InputLabel for="hmac_algorithm" value="HMAC Signature  Algorithm" />
                    <select
                        id="hmac_algorithm"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="createWebhookForm.hmac_algorithm"
                    >
                        <option v-for="hmacAlgorithm in hmacAlgorithms" :value="hmacAlgorithm">{{ hmacAlgorithm }}</option>
                    </select>
                    <p class="mt-2 text-xs text-gray-600">Which hmac signature algorithm should we sign the webhook payload with?</p>
                    <InputError :message="createWebhookForm.errors.hmac_algorithm" class="mt-2" />
                </div>

                <!-- Max Attempts -->
                <div class="col-span-6">
                    <InputLabel for="max_attempts" value="Max Attempts" />
                    <TextInput
                        id="max_attempts"
                        placeholder="e.g. 10"
                        v-model="createWebhookForm.max_attempts"
                        type="number"
                        min="1"
                        step="1"
                        max="60"
                        class="mt-1 block w-full"
                        required
                    />
                    <p class="mt-2 text-xs text-gray-600">Max attempts to call your webhook url?</p>
                    <InputError :message="createWebhookForm.errors.max_attempts" class="mt-2" />
                </div>

                <!-- Timeout Seconds -->
                <div class="col-span-6">
                    <InputLabel for="timeout_seconds" value="Timeout (Seconds)" />
                    <TextInput
                        id="timeout_seconds"
                        placeholder="e.g. 10"
                        v-model="createWebhookForm.timeout_seconds"
                        type="number"
                        min="1"
                        step="1"
                        max="60"
                        class="mt-1 block w-full"
                        required
                    />
                    <p class="mt-2 text-xs text-gray-600">How long should we wait for the request (to your webhook url) to succeed?</p>
                    <InputError :message="createWebhookForm.errors.timeout_seconds" class="mt-2" />
                </div>

                <!-- Retry Delay Seconds -->
                <div class="col-span-6">
                    <InputLabel for="retry_seconds" value="Retry Delay (Seconds)" />
                    <TextInput
                        id="retry_seconds"
                        placeholder="e.g. 10"
                        v-model="createWebhookForm.retry_seconds"
                        type="number"
                        min="1"
                        step="1"
                        max="900"
                        class="mt-1 block w-full"
                        required
                    />
                    <p class="mt-2 text-xs text-gray-600">Delay between retry attempts?</p>
                    <InputError :message="createWebhookForm.errors.retry_seconds" class="mt-2" />
                </div>

                <!-- Event Targets -->
                <div v-if="eventTargetNames.length > 0" class="col-span-12">
                    <InputLabel for="eventTargetNames" value="Event Targets" />

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="eventTargetName in eventTargetNames" :key="eventTargetName">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="createWebhookForm.target_events" :value="eventTargetName" />
                                <span class="ms-2 text-sm text-gray-600">{{ eventTargetName }}</span>
                            </label>
                        </div>
                    </div>
                </div>

            </template>

            <template #actions>
                <ActionMessage :on="createWebhookForm.recentlySuccessful" class="me-3">
                    Created.
                </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': createWebhookForm.processing }" :disabled="createWebhookForm.processing">
                    Create
                </PrimaryButton>
            </template>

        </FormSection>

        <!-- Manage Webhooks -->
        <div v-if="webhooks.length > 0">
            <SectionBorder />
            <div class="mt-10 sm:mt-0">
                <ActionSection>
                    <template #title>
                        Manage Webhooks
                    </template>

                    <template #description>
                        You may delete any of your existing webhooks if they are no longer needed.
                    </template>

                    <!-- Webhook List -->
                    <template #content>
                        <div class="space-y-6">
                            <div v-for="webhook in webhooks" :key="webhook.id" class="flex items-center justify-between">
                                <div class="break-all">
                                    {{ webhook.url }}
                                </div>

                                <div class="flex items-center ms-2">
                                    <button
                                        v-if="eventTargetNames.length > 0"
                                        class="cursor-pointer ms-6 text-sm text-gray-400 underline"
                                        @click="manageWebhookEventTargets(webhook)"
                                    >
                                        Target Events
                                    </button>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500" @click="confirmWebhookDeletion(webhook)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </ActionSection>
            </div>
        </div>

        <!-- Secret Value Modal -->
        <DialogModal :show="displayingSecret" @close="displayingSecret = false">
            <template #title>
                Webhook HMAC Verification Secret
            </template>

            <template #content>
                <div>
                    Please copy your webhook HMAC verification secret, it is used to implement HMAC signature verification on your end. For your security, it won't be shown again.
                </div>

                <div v-if="$page.props.jetstream.flash.secret" class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 break-all">
                    {{ $page.props.jetstream.flash.secret }}
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="displayingSecret = false">
                    Close
                </SecondaryButton>
            </template>
        </DialogModal>

        <!-- Webhook Event Targets Modal -->
        <!-- TODO -->

        <!-- Delete Webhook Confirmation Modal -->
        <ConfirmationModal :show="webhookBeingDeleted != null" @close="webhookBeingDeleted = null">
            <template #title>
                Delete Webhook
            </template>

            <template #content>
                Are you sure you would like to delete this webhook?
            </template>

            <template #footer>
                <SecondaryButton @click="webhookBeingDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteWebhookForm.processing }"
                    :disabled="deleteWebhookForm.processing"
                    @click="deleteWebhook"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>

    </div>
</template>
