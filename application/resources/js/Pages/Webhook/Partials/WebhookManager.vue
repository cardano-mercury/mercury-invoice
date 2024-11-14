<script setup>
import {ref} from 'vue';
import {useForm} from "@inertiajs/vue3";
import FormSection from "@/Components/FormSection.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import ActionSection from "@/Components/ActionSection.vue";

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
            alert(`There was an error while creating webhook: ${JSON.stringify(e)}`);
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
            alert(`There was an error while updating webhook: ${JSON.stringify(e)}`);
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
            alert(`There was an error while deleting webhook: ${JSON.stringify(e)}`);
        }
    });
};

</script>

<template>

    <!-- Register New Webhook -->
    <FormSection @submitted="createWebhook">

        <template #title>
            <h2>Register New Webhook</h2>
        </template>

        <template #description>
            Register your own webhook urls to get notified, when interested
            events occurs in your account.
        </template>

        <template #form>

            <!-- Webhook URL -->
            <v-text-field type="url" label="Webhook URL"
                          v-model="createWebhookForm.url" autofocus required/>

            <!-- HMAC  Signature Algorithm -->
            <v-select v-model="createWebhookForm.hmac_algorithm"
                      :items="hmacAlgorithms" label="HMAC Signature Algorithm"/>

            <!-- Max Attempts -->
            <v-text-field label="Max Attempts" type="number"
                          v-model="createWebhookForm.max_attempts" required
                          min="1" step="1" max="60"/>

            <!-- Timeout Seconds -->
            <v-text-field type="number" min="1" step="1" max="60"
                          label="Timeout (Seconds)"
                          v-model="createWebhookForm.timeout_seconds"
                          hint="How long should we wait for the request (to your webhook URL) to succeed?"
                          persistent-hint required/>

            <!-- Retry Delay Seconds -->
            <v-text-field type="number" min="1" step="1" max="900"
                          label="Retry Delay (Seconds)"
                          v-model="createWebhookForm.retry_seconds"
                          hint="Delay between retry attempts" persistent-hint
                          required/>

            <!-- Event Targets -->
            <template v-if="eventTargetNames.length">
                <h3 class="mt-4">Event Targets</h3>
                <v-row>
                    <v-col v-for="eventTargetName in eventTargetNames"
                           :key="eventTargetName" cols="12" md="6">
                        <v-checkbox v-model="createWebhookForm.target_events"
                                    :value="eventTargetName"
                                    :label="eventTargetName"/>
                    </v-col>
                </v-row>
            </template>
        </template>

        <template #actions>
            <v-btn
                color="primary"
                :disabled="createWebhookForm.processing"
                variant="flat"
                @click="createWebhook"
            >
                Create
            </v-btn>
        </template>

    </FormSection>

    <!-- Manage Webhooks -->
    <div v-if="webhooks.length > 0">
        <SectionBorder/>
        <div class="mt-10 sm:mt-0">
            <ActionSection>
                <template #title>
                    <h2>Manage Webhooks</h2>
                </template>

                <template #description>
                    You may delete any of your existing webhooks if they are no
                    longer needed.
                </template>

                <!-- Webhook List -->
                <template #content>
                    <v-list>
                        <v-list-item v-for="webhook in webhooks"
                                     :key="webhook.id">
                            <v-list-item-title>{{ webhook.url }}
                            </v-list-item-title>
                            <v-divider class="my-2"/>
                            <v-list-item-subtitle>
                                Configuration:
                                <v-chip label class="me-2">
                                    {{ webhook.hmac_algorithm }} HMAC Algo
                                </v-chip>
                                <v-chip label class="me-2">
                                    {{ webhook.max_attempts }} Max Attempts
                                </v-chip>
                                <v-chip label class="me-2">
                                    {{ webhook.timeout_seconds }}s Timeout
                                </v-chip>
                                <v-chip label class="me-2">
                                    {{ webhook.retry_seconds }}s Retry
                                </v-chip>
                            </v-list-item-subtitle>
                            <v-divider class="my-2"/>
                            <v-list-item-subtitle>
                                Event Targets:
                                <template
                                    v-if="webhook.event_targets.length > 0">
                                    <v-chip label class="me-2"
                                            v-for="event_target in webhook.event_targets"
                                            :key="event_target.event_name">
                                        {{ event_target.event_name }}
                                    </v-chip>
                                </template>
                                <template v-else>
                                    <v-chip label>
                                        None Selected
                                    </v-chip>
                                </template>
                            </v-list-item-subtitle>
                            <v-menu>
                                <template v-slot:activator="{ props }">
                                    <v-btn color="secondary" v-bind="props"
                                           class="my-4">
                                        Actions
                                        <v-icon icon="mdi-chevron-down"/>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item
                                        :href="route('webhooks.logs', webhook)">
                                        View Logs
                                    </v-list-item>
                                    <v-list-item
                                        :href="route('webhooks.test', webhook)">
                                        Test Webhook
                                    </v-list-item>
                                    <v-list-item v-if="eventTargetNames.length"
                                                 @click="manageWebhookEventTargets(webhook)">
                                        Update Webhook
                                    </v-list-item>
                                    <v-list-item
                                        @click="confirmWebhookDeletion(webhook)"
                                        class="text-error">
                                        Delete Webhook
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </v-list-item>
                    </v-list>
                </template>
            </ActionSection>
        </div>
    </div>

    <!-- Secret Value Modal -->
    <v-dialog persistent v-model="displayingSecret" width="auto">
        <v-card>
            <v-card-title>Webhook HMAC Verification Secret</v-card-title>
            <v-card-text>
                <div>
                    Please copy your webhook HMAC verification secret, it is
                    used to
                    implement HMAC signature verification on your end. For your
                    security, it won't be shown again.
                </div>

                <v-alert v-if="$page.props.jetstream.flash.secret" type="info">
                    <code>{{ $page.props.jetstream.flash.secret }}</code>
                </v-alert>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="displayingSecret = false">
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Update Webhook Modal -->
    <v-dialog persistent v-model="webhookBeingUpdated" width="auto">
        <v-card>
            <v-card-title>Update Webhook</v-card-title>
            <v-card-text>
                <!-- Webhook URL -->
                <v-text-field type="url" label="Webhook URL"
                              v-model="updateWebhookForm.url" autofocus
                              required/>

                <!-- HMAC  Signature Algorithm -->
                <v-select v-model="updateWebhookForm.hmac_algorithm"
                          :items="hmacAlgorithms"
                          label="HMAC Signature Algorithm"/>

                <!-- Max Attempts -->
                <v-text-field label="Max Attempts" type="number"
                              v-model="updateWebhookForm.max_attempts" required
                              min="1" step="1" max="60"/>

                <!-- Timeout Seconds -->
                <v-text-field type="number" min="1" step="1" max="60"
                              label="Timeout (Seconds)"
                              v-model="updateWebhookForm.timeout_seconds"
                              hint="How long should we wait for the request (to your webhook URL) to succeed?"
                              persistent-hint required/>

                <!-- Retry Delay Seconds -->
                <v-text-field type="number" min="1" step="1" max="900"
                              label="Retry Delay (Seconds)"
                              v-model="updateWebhookForm.retry_seconds"
                              hint="Delay between retry attempts"
                              persistent-hint
                              required/>

                <template v-if="eventTargetNames.length">
                    <h3 class="mt-4">Event Targets</h3>
                    <v-row>
                        <v-col v-for="eventTargetName in eventTargetNames"
                               :key="eventTargetName" cols="12" md="6">
                            <v-checkbox
                                v-model="updateWebhookForm.target_events"
                                :value="eventTargetName"
                                :label="eventTargetName"/>
                        </v-col>
                    </v-row>
                </template>

            </v-card-text>
            <v-card-actions>
                <v-btn @click="webhookBeingUpdated = null">Cancel</v-btn>
                <v-btn
                    color="primary"
                    :disabled="updateWebhookForm.processing"
                    variant="flat"
                    @click="updateWebhook"
                >
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Delete Webhook Confirmation Modal -->
    <v-dialog v-model="webhookBeingDeleted" persistent width="auto">
        <v-card>
            <v-card-title>Delete Webhook</v-card-title>
            <v-card-text>
                Are you sure you would like to delete this webhook?
            </v-card-text>
            <v-card-actions>
                <v-btn @click="webhookBeingDeleted = null">
                    Cancel
                </v-btn>
                <v-btn color="error" :disabled="deleteWebhookForm.processing"
                       @click="deleteWebhook">
                    Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
