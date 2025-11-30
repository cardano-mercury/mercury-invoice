<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import FormCard from '@/Components/FormCard.vue';
import { router as Inertia, useForm } from '@inertiajs/vue3';

const props = defineProps({
    errors: Object,
    service: Object,
    serviceCategories: Array,
});

// Basic service info form
const basicInfoForm = useForm(props.service);

// Active tab management
const activeTab = ref('basic');

// Category management
const selectedCategories = ref(props.service.categories?.map((cat) => cat.id) || []);

function updateCategories() {
    Inertia.put(
        route('services.categories.update', props.service.id),
        {
            service_id: props.service.id,
            category_ids: selectedCategories.value,
        },
        {
            preserveScroll: true,
        }
    );
}

// Category CRUD
const categoryForm = useForm({
    name: '',
});

const editingCategory = ref(null);

function addCategory() {
    categoryForm.post(route('service-categories.store'), {
        preserveScroll: true,
        onSuccess: () => {
            categoryForm.reset();
        },
    });
}

function editCategory(category) {
    editingCategory.value = category;
    categoryForm.name = category.name;
}

function updateCategory() {
    if (!editingCategory.value) return;

    categoryForm.put(route('service-categories.update', editingCategory.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            categoryForm.reset();
            editingCategory.value = null;
        },
    });
}

function cancelCategoryEdit() {
    editingCategory.value = null;
    categoryForm.reset();
}

function deleteCategory(category) {
    if (confirm('Are you sure you want to delete this category? It will be removed from all services.')) {
        Inertia.delete(route('service-categories.destroy', category.id), {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <AppLayout title="Update Service">
        <template #header>
            <PageHeader 
                title="Update Service" 
                :subtitle="service.name"
                icon="mdi-briefcase-edit"
            >
                <template #actions>
                    <v-btn
                        :href="route('services.show', service.id)"
                        variant="tonal"
                        color="primary"
                        prepend-icon="mdi-eye"
                    >
                        View
                    </v-btn>
                </template>
            </PageHeader>
        </template>

        <v-card>
            <v-tabs v-model="activeTab" color="primary" grow>
                <v-tab value="basic" prepend-icon="mdi-briefcase">
                    Basic Info
                </v-tab>
                <v-tab value="categories" prepend-icon="mdi-tag-multiple">
                    Categories
                    <v-badge 
                        v-if="service.categories?.length" 
                        :content="service.categories.length" 
                        color="primary"
                        inline
                        class="ml-2"
                    />
                </v-tab>
            </v-tabs>

            <v-divider />

            <v-card-text class="pa-6">
                <v-window v-model="activeTab">
                    <!-- Basic Info Tab -->
                    <v-window-item value="basic">
                        <v-form @submit.prevent="basicInfoForm.put(route('services.update', service.id))">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="basicInfoForm.name"
                                        label="Service Name"
                                        placeholder="e.g. Software Development"
                                        prepend-inner-icon="mdi-briefcase"
                                        :error-messages="basicInfoForm.errors.name"
                                        required
                                        autofocus
                                    />
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12">
                                    <v-textarea
                                        v-model="basicInfoForm.description"
                                        label="Description"
                                        placeholder="e.g. Professional software development services including..."
                                        prepend-inner-icon="mdi-text"
                                        :error-messages="basicInfoForm.errors.description"
                                        rows="3"
                                        auto-grow
                                    />
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="basicInfoForm.unit_price"
                                        label="Unit Price (Hourly Rate)"
                                        placeholder="e.g. 150.00"
                                        prepend-inner-icon="mdi-currency-usd"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        :error-messages="basicInfoForm.errors.unit_price"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="basicInfoForm.supplier"
                                        label="Service Provider"
                                        placeholder="e.g. Your Company Name"
                                        prepend-inner-icon="mdi-domain"
                                        :error-messages="basicInfoForm.errors.supplier"
                                    />
                                </v-col>
                            </v-row>

                            <div class="d-flex justify-end mt-6">
                                <v-btn
                                    type="submit"
                                    color="primary"
                                    variant="flat"
                                    prepend-icon="mdi-content-save"
                                    :loading="basicInfoForm.processing"
                                >
                                    Update Service
                                </v-btn>
                            </div>
                        </v-form>
                    </v-window-item>

                    <!-- Categories Tab -->
                    <v-window-item value="categories">
                        <!-- Assign Categories -->
                        <FormCard 
                            title="Assign Categories" 
                            icon="mdi-tag-check"
                            subtitle="Select categories to assign to this service"
                            flat
                        >
                            <v-form @submit.prevent="updateCategories">
                                <v-select
                                    v-model="selectedCategories"
                                    :items="serviceCategories"
                                    item-title="name"
                                    item-value="id"
                                    label="Select Categories"
                                    prepend-inner-icon="mdi-tag-multiple"
                                    multiple
                                    chips
                                    closable-chips
                                    :error-messages="errors.category_ids"
                                />

                                <div class="d-flex justify-end mt-4">
                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        variant="flat"
                                        prepend-icon="mdi-tag-check"
                                    >
                                        Update Categories
                                    </v-btn>
                                </div>
                            </v-form>
                        </FormCard>

                        <!-- Manage Categories -->
                        <FormCard 
                            :title="editingCategory ? 'Edit Category' : 'Create New Category'" 
                            icon="mdi-tag-plus"
                            class="mt-4"
                            flat
                        >
                            <v-form @submit.prevent="editingCategory ? updateCategory() : addCategory()">
                                <v-row align="center">
                                    <v-col cols="12" sm="8">
                                        <v-text-field
                                            v-model="categoryForm.name"
                                            label="Category Name"
                                            placeholder="e.g. Web Development"
                                            prepend-inner-icon="mdi-tag"
                                            :error-messages="categoryForm.errors.name"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <div class="d-flex ga-2">
                                            <v-btn
                                                type="submit"
                                                color="primary"
                                                variant="flat"
                                                :prepend-icon="editingCategory ? 'mdi-content-save' : 'mdi-plus'"
                                                :loading="categoryForm.processing"
                                            >
                                                {{ editingCategory ? 'Update' : 'Create' }}
                                            </v-btn>
                                            <v-btn
                                                v-if="editingCategory"
                                                variant="tonal"
                                                color="secondary"
                                                prepend-icon="mdi-close"
                                                @click="cancelCategoryEdit"
                                            >
                                                Cancel
                                            </v-btn>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </FormCard>

                        <!-- All Categories Table -->
                        <v-card variant="outlined" class="mt-4">
                            <v-card-title class="d-flex align-center">
                                <v-icon icon="mdi-tag-multiple" class="mr-2" />
                                All Service Categories
                            </v-card-title>
                            <v-divider />
                            <v-data-table
                                v-if="serviceCategories && serviceCategories.length > 0"
                                :items="serviceCategories"
                                :headers="[
                                    { title: 'Category Name', key: 'name' },
                                    { title: 'Actions', key: 'actions', sortable: false, align: 'end' },
                                ]"
                                density="comfortable"
                                hide-default-footer
                            >
                                <template #item.name="{ item }">
                                    <v-chip variant="tonal" color="primary">
                                        <v-icon start icon="mdi-tag" />
                                        {{ item.name }}
                                    </v-chip>
                                </template>
                                <template #item.actions="{ item }">
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-pencil"
                                        color="primary"
                                        @click="editCategory(item)"
                                    />
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-delete"
                                        color="error"
                                        @click="deleteCategory(item)"
                                    />
                                </template>
                            </v-data-table>
                            <v-card-text v-else class="text-center text-medium-emphasis py-8">
                                <v-icon icon="mdi-tag-off" size="48" class="mb-2" />
                                <p>No service categories created yet.</p>
                            </v-card-text>
                        </v-card>
                    </v-window-item>
                </v-window>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
