<script setup>
import {ref, computed} from 'vue';
import {useForm} from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import FormSection from '@/Components/FormSection.vue';
import SectionBorder from '@/Components/SectionBorder.vue';

const props = defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

const createApiTokenForm = useForm({
    name: '',
    permissions: props.defaultPermissions,
});

const updateApiTokenForm = useForm({
    permissions: [],
});

const deleteApiTokenForm = useForm({});

const displayingToken = ref(false);
const managingPermissionsFor = ref(null);
const apiTokenBeingDeleted = ref(null);
const showSuccessMessage = ref(false);

// Group permissions by their prefix (before the colon)
const groupedPermissions = computed(() => {
    const groups = {};
    
    props.availablePermissions.forEach(permission => {
        const [group, action] = permission.split(':');
        
        if (!groups[group]) {
            groups[group] = [];
        }
        
        groups[group].push({
            fullName: permission,
            action: action
        });
    });
    
    return groups;
});

const createFormSelectedCounts = computed(() => {
    const counts = {};
    for (const group in groupedPermissions.value) {
        counts[group] = groupedPermissions.value[group].filter(permission => 
            createApiTokenForm.permissions.includes(permission.fullName)
        ).length;
    }
    return counts;
});

const updateFormSelectedCounts = computed(() => {
    const counts = {};
    for (const group in groupedPermissions.value) {
        counts[group] = groupedPermissions.value[group].filter(permission => 
            updateApiTokenForm.permissions.includes(permission.fullName)
        ).length;
    }
    return counts;
});

const createApiToken = () => {
    createApiTokenForm.post(route('api-tokens.store'), {
        preserveScroll: true,
        onSuccess: (response) => {
            displayingToken.value = true;
            showSuccessMessage.value = true;
            createApiTokenForm.reset();
        },
    });
};

const manageApiTokenPermissions = (token) => {
    updateApiTokenForm.permissions = token.abilities;
    managingPermissionsFor.value = token;
};

const updateApiToken = () => {
    updateApiTokenForm.put(route('api-tokens.update', managingPermissionsFor.value), {
        preserveScroll: true,
        onSuccess: () => {
            managingPermissionsFor.value = null;
            showSuccessMessage.value = true;
        },
    });
};

const confirmApiTokenDeletion = (token) => {
    apiTokenBeingDeleted.value = token;
};

const deleteApiToken = () => {
    deleteApiTokenForm.delete(route('api-tokens.destroy', apiTokenBeingDeleted.value), {
        preserveScroll: true,
        onSuccess: () => {
            apiTokenBeingDeleted.value = null;
            showSuccessMessage.value = true;
        },
    });
};

</script>

<template>
    <v-card class="mb-6">
        <v-card-title>Create API Token</v-card-title>
        <v-card-subtitle>
            API tokens allow third-party services to authenticate with our application on your behalf.
        </v-card-subtitle>

        <v-card-text>
            <v-form @submit.prevent="createApiToken">
                <!-- Token Name -->
                <v-text-field
                    v-model="createApiTokenForm.name"
                    label="API TokenName"
                    :error-messages="createApiTokenForm.errors.name"
                ></v-text-field>

                <!-- Token Permissions -->
                <div v-if="availablePermissions.length > 0">
                    <v-card-subtitle class="px-0">Permissions</v-card-subtitle>

                    <v-expansion-panels variant="accordion" class="mt-2">
                        <v-expansion-panel
                            v-for="(permissions, group) in groupedPermissions"
                            :key="group"
                        >
                            <v-expansion-panel-title>
                                {{ group }} 
                                <v-chip
                                    v-if="createFormSelectedCounts[group] > 0"
                                    size="small"
                                    class="ml-2"
                                >
                                    {{ createFormSelectedCounts[group] }} / {{ permissions.length }}
                                </v-chip>
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                                <div class="d-flex flex-wrap">
                                    <v-checkbox
                                        v-for="permission in permissions"
                                        :key="permission.fullName"
                                        v-model="createApiTokenForm.permissions"
                                        :label="permission.action"
                                        :value="permission.fullName"
                                        hide-details
                                        density="compact"
                                        class="mr-4 mb-2"
                                    ></v-checkbox>
                                </div>
                            </v-expansion-panel-text>
                        </v-expansion-panel>
                    </v-expansion-panels>
                </div>

                <div class="d-flex justify-end mt-4">
                    <v-snackbar
                        v-model="showSuccessMessage"
                        color="success"
                        timeout="3000"
                    >
                        API token created successfully!
                    </v-snackbar>
                    
                    <v-btn
                        color="primary"
                        :loading="createApiTokenForm.processing"
                        type="submit"
                    >
                        Create
                    </v-btn>
                </div>
            </v-form>
        </v-card-text>
    </v-card>

    <!-- Display Token Modal -->
    <v-dialog v-model="displayingToken" max-width="500px">
        <v-card>
            <v-card-title>API Token</v-card-title>
            <v-card-text>
                <div>
                    Please copy your new API token. For your security, it won't be shown again.
                </div>

                <div v-if="$page.props.jetstream.flash.token" class="mt-4 pa-4 bg-grey-lighten-4 rounded font-mono text-sm overflow-auto">
                    {{ $page.props.jetstream.flash.token }}
                </div>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    @click="displayingToken = false"
                >
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Manage API Tokens -->
    <v-card v-if="tokens.length > 0">
        <v-card-title>Manage API Tokens</v-card-title>
        <v-card-subtitle>
            You can delete any of your existing tokens if they are no longer needed.
        </v-card-subtitle>

        <v-card-text>
            <!-- API Token List -->
            <div class="space-y-6">
                <div v-for="token in tokens" :key="token.id" class="d-flex justify-space-between items-center mb-4">
                    <div>
                        {{ token.name }}
                    </div>

                    <div class="d-flex align-center">
                        <div v-if="token.last_used_at" class="text-sm text-gray-400 mr-4">
                            Last used: {{ token.last_used_at }}
                        </div>

                        <v-btn
                            color="secondary"
                            variant="outlined"
                            size="small"
                            class="mr-2"
                            @click="manageApiTokenPermissions(token)"
                        >
                            Permissions
                        </v-btn>

                        <v-btn
                            color="error"
                            variant="outlined"
                            size="small"
                            @click="confirmApiTokenDeletion(token)"
                        >
                            Delete
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-card-text>
    </v-card>

    <!-- Token Permissions Modal -->
    <v-dialog v-model="managingPermissionsFor" max-width="500px">
        <v-card>
            <v-card-title>API Token Permissions</v-card-title>
            <v-card-text>
                <v-expansion-panels variant="accordion" class="mt-2">
                    <v-expansion-panel
                        v-for="(permissions, group) in groupedPermissions"
                        :key="group"
                    >
                        <v-expansion-panel-title>
                            {{ group }}
                            <v-chip
                                v-if="updateFormSelectedCounts[group] > 0"
                                size="small"
                                class="ml-2"
                            >
                                {{ updateFormSelectedCounts[group] }} / {{ permissions.length }}
                            </v-chip>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            <div class="d-flex flex-wrap">
                                <v-checkbox
                                    v-for="permission in permissions"
                                    :key="permission.fullName"
                                    v-model="updateApiTokenForm.permissions"
                                    :label="permission.action"
                                    :value="permission.fullName"
                                    hide-details
                                    density="compact"
                                    class="mr-4 mb-2"
                                ></v-checkbox>
                            </div>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="secondary"
                    @click="managingPermissionsFor = null"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="primary"
                    :loading="updateApiTokenForm.processing"
                    @click="updateApiToken"
                >
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Delete Token Confirmation Modal -->
    <v-dialog v-model="apiTokenBeingDeleted" max-width="500px">
        <v-card>
            <v-card-title>Delete API Token</v-card-title>
            <v-card-text>
                Are you sure you would like to delete this API token?
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="secondary"
                    @click="apiTokenBeingDeleted = null"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="error"
                    :loading="deleteApiTokenForm.processing"
                    @click="deleteApiToken"
                >
                    Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
