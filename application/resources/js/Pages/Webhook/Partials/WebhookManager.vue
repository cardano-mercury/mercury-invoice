<script setup>
import { ref } from 'vue';
import {Link, useForm} from "@inertiajs/vue3";
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
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

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
    url: null,
    hmac_algorithm: null,
    max_attempts: null,
    timeout_seconds: null,
    retry_seconds: null,
    target_events: [],
});

const deleteWebhookForm = useForm({});

const displayingSecret = ref(false);
const webhookBeingUpdated = ref(null);
const webhookBeingDeleted = ref(null);

const createWebhook = () => {
    createWebhookForm.post(route('webhooks.store'), {
        preserveScroll: true,
        onSuccess: () => {
            displayingSecret.value = true;
            createWebhookForm.reset();
        },
        onError: (e) => {
            alert(`There was an error while creating webhook: ${ JSON.stringify(e) }`);
        }
    });
};

const manageWebhookEventTargets = (webhook) => {
    updateWebhookForm.url = webhook.url;
    updateWebhookForm.hmac_algorithm = webhook.hmac_algorithm;
    updateWebhookForm.max_attempts = webhook.max_attempts;
    updateWebhookForm.timeout_seconds = webhook.timeout_seconds;
    updateWebhookForm.retry_seconds = webhook.retry_seconds;
    const targetEvents = [];
    webhook.event_targets.forEach((eventTarget) => {
        targetEvents.push(eventTarget.event_name);
    });
    updateWebhookForm.target_events = targetEvents;
    webhookBeingUpdated.value = webhook;
};

const updateWebhook = () => {
    updateWebhookForm.put(route('webhooks.update', webhookBeingUpdated.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            webhookBeingUpdated.value = null;
            updateWebhookForm.reset();
        },
        onError: (e) => {
            alert(`There was an error while updating webhook: ${ JSON.stringify(e) }`);
        }
    });
};

const confirmWebhookDeletion = (webhook) => {
    webhookBeingDeleted.value = webhook;
};

const deleteWebhook = () => {
    deleteWebhookForm.delete(route('webhooks.destroy', webhookBeingDeleted.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (webhookBeingDeleted.value = null),
        onError: (e) => {
            alert(`There was an error while deleting webhook: ${ JSON.stringify(e) }`);
        }
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
                    <InputLabel for="hmac_algorithm" value="HMAC Signature Algorithm" />
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
                            <div v-for="webhook in webhooks" :key="webhook.id" class="flex items-center justify-between bg-gray-50 p-3 sm:rounded-lg">
                                <div class="break-all">
                                    <div>
                                        {{ webhook.url }}
                                    </div>
                                    <hr class="my-2">
                                    <div>
                                        <span class="text-sm pr-1">Configuration</span>
                                        <span class="sm-badge">{{ webhook.hmac_algorithm }} HMAC Algo</span>
                                        <span class="sm-badge">{{ webhook.max_attempts }} Max Attempts</span>
                                        <span class="sm-badge">{{ webhook.timeout_seconds }}s Timeout</span>
                                        <span class="sm-badge">{{ webhook.retry_seconds }}s Retry</span>
                                    </div>
                                    <div>
                                        <span class="text-sm pr-1">Event Targets</span>
                                        <span v-if="webhook.event_targets.length > 0" v-for="event_target in webhook.event_targets" class="sm-badge">
                                            {{ event_target.event_name }}
                                        </span>
                                        <span v-if="webhook.event_targets.length === 0" class="sm-badge">
                                            None Selected
                                        </span>
                                    </div>
                                </div>

                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                Actions
                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            View Logs
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('webhooks.test', webhook)"
                                        >
                                            Test Webhook
                                        </DropdownLink>
                                        <DropdownLink
                                            as="button"
                                            v-if="eventTargetNames.length > 0"
                                            @click="manageWebhookEventTargets(webhook)"
                                        >
                                            Update Webhook
                                        </DropdownLink>
                                        <DropdownLink
                                            as="button"
                                            @click="confirmWebhookDeletion(webhook)"
                                        >
                                            <span class="text-red-500">
                                                Delete Webhook
                                            </span>
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
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

        <!-- Update Webhook Modal -->
        <DialogModal :show="webhookBeingUpdated != null" @close="webhookBeingUpdated = null">
            <template #title>
                Update Webhook
            </template>

            <template #content>
                <div>

                    <!-- Webhook URL -->
                    <div class="col-span-12 mb-3">
                        <InputLabel for="url" value="Webhook URL" />
                        <TextInput
                            id="url"
                            placeholder="e.g. https://your-website.com/webhooks/handler"
                            v-model="updateWebhookForm.url"
                            type="text"
                            class="mt-1 block w-full"
                            autofocus
                            required
                        />
                        <InputError :message="updateWebhookForm.errors.url" class="mt-2" />
                    </div>

                    <!-- HMAC  Signature Algorithm -->
                    <div class="col-span-12 mb-3">
                        <InputLabel for="hmac_algorithm" value="HMAC Signature Algorithm" />
                        <select
                            id="hmac_algorithm"
                            class="border-gray-300 max-w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            v-model="updateWebhookForm.hmac_algorithm"
                        >
                            <option v-for="hmacAlgorithm in hmacAlgorithms" :value="hmacAlgorithm">{{ hmacAlgorithm }}</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-600">Which hmac signature algorithm should we sign the webhook payload with?</p>
                        <InputError :message="updateWebhookForm.errors.hmac_algorithm" class="mt-2" />
                    </div>

                    <!-- Max Attempts -->
                    <div class="col-span-12 mb-3">
                        <InputLabel for="max_attempts" value="Max Attempts" />
                        <TextInput
                            id="max_attempts"
                            placeholder="e.g. 10"
                            v-model="updateWebhookForm.max_attempts"
                            type="number"
                            min="1"
                            step="1"
                            max="60"
                            class="mt-1 block w-full"
                            required
                        />
                        <p class="mt-1 text-xs text-gray-600">Max attempts to call your webhook url?</p>
                        <InputError :message="updateWebhookForm.errors.max_attempts" class="mt-2" />
                    </div>

                    <!-- Timeout Seconds -->
                    <div class="col-span-12 mb-3">
                        <InputLabel for="timeout_seconds" value="Timeout (Seconds)" />
                        <TextInput
                            id="timeout_seconds"
                            placeholder="e.g. 10"
                            v-model="updateWebhookForm.timeout_seconds"
                            type="number"
                            min="1"
                            step="1"
                            max="60"
                            class="mt-1 block w-full"
                            required
                        />
                        <p class="mt-1 text-xs text-gray-600">How long should we wait for the request (to your webhook url) to succeed?</p>
                        <InputError :message="updateWebhookForm.errors.timeout_seconds" class="mt-2" />
                    </div>

                    <!-- Retry Delay Seconds -->
                    <div class="col-span-12 mb-3">
                        <InputLabel for="retry_seconds" value="Retry Delay (Seconds)" />
                        <TextInput
                            id="retry_seconds"
                            placeholder="e.g. 10"
                            v-model="updateWebhookForm.retry_seconds"
                            type="number"
                            min="1"
                            step="1"
                            max="900"
                            class="mt-1 block w-full"
                            required
                        />
                        <p class="mt-1 text-xs text-gray-600">Delay between retry attempts?</p>
                        <InputError :message="updateWebhookForm.errors.retry_seconds" class="mt-2" />
                    </div>

                    <!-- Event Targets -->
                    <div v-if="eventTargetNames.length > 0" class="col-span-12">
                        <InputLabel for="eventTargetNames" value="Event Targets" />
                        <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="eventTargetName in eventTargetNames" :key="eventTargetName">
                                <label class="flex items-center">
                                    <Checkbox v-model:checked="updateWebhookForm.target_events" :value="eventTargetName" />
                                    <span class="ms-2 text-sm text-gray-600">{{ eventTargetName }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="webhookBeingUpdated = null">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': updateWebhookForm.processing }"
                    :disabled="updateWebhookForm.processing"
                    @click="updateWebhook"
                >
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>

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
