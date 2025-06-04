<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router as Inertia, useForm } from '@inertiajs/vue3';

const props = defineProps({
    errors: Object, 
    service: Object,
    serviceCategories: Array
});

// Basic service info form
const basicInfoForm = useForm(props.service);

// Active tab management
const activeTab = ref('basic');

// Category management
const selectedCategories = ref(props.service.categories?.map(cat => cat.id) || []);

function updateCategories() {
    Inertia.put(route('services.categories.update', props.service.id), {
        service_id: props.service.id,
        category_ids: selectedCategories.value
    }, {
        preserveScroll: true
    });
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
        }
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
        }
    });
}

function cancelCategoryEdit() {
    editingCategory.value = null;
    categoryForm.reset();
}

function deleteCategory(category) {
    if (confirm('Are you sure you want to delete this category? It will be removed from all services.')) {
        Inertia.delete(route('service-categories.destroy', category.id), {
            preserveScroll: true
        });
    }
}
</script>

<template>
    <app-layout title="Update Service">
        <template #header>
            <h1>Update Service</h1>
        </template>

        <v-card class="mb-4">
            <v-tabs v-model="activeTab">
                <v-tab value="basic">Basic Info</v-tab>
                <v-tab value="categories">Categories</v-tab>
            </v-tabs>

            <v-card-text class="bg-white px-4 py-12">
                <!-- Basic Info Tab -->
                <v-window v-model="activeTab">
                    <v-window-item value="basic">
                        <v-form @submit.prevent="basicInfoForm.put(route('services.update', service.id))">
                            <v-text-field 
                                name="name" 
                                label="Service Name" 
                                placeholder="e.g. Software Development"
                                v-model="basicInfoForm.name"
                                :error-messages="basicInfoForm.errors.name"
                                required 
                                autofocus
                            />
                            <v-textarea 
                                name="description"
                                label="Description" 
                                placeholder=".e.g. My hourly rate for software development..."
                                v-model="basicInfoForm.description"
                                :error-messages="basicInfoForm.errors.description"
                            />
                            <v-text-field 
                                name="unit_price" 
                                type="number"
                                label="Unit Price" 
                                placeholder="e.g. 5.99"
                                v-model="basicInfoForm.unit_price" 
                                :error-messages="basicInfoForm.errors.unit_price"
                                required 
                                step="any" 
                                min="0" 
                            />
                            <v-text-field 
                                name="supplier" 
                                label="Service Supplier" 
                                placeholder="e.g. ACME Limited"
                                v-model="basicInfoForm.supplier"
                                :error-messages="basicInfoForm.errors.supplier"
                            />
                            <v-row>
                                <v-col class="d-flex ga-2">
                                    <v-btn color="primary" type="submit" variant="flat" prepend-icon="mdi-content-save">Update Basic Info</v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-window-item>

                    <!-- Categories Tab -->
                    <v-window-item value="categories">
                        <v-card variant="text">
                            <v-card-title class="text-h6">
                                Assign Categories
                            </v-card-title>

                            <!-- Categories Form -->
                            <v-card-text>
                                <v-form @submit.prevent="updateCategories">
                                    <v-row>
                                        <v-col cols="12">
                                            <v-select
                                                v-model="selectedCategories"
                                                :items="serviceCategories"
                                                item-title="name"
                                                item-value="id"
                                                label="Select Categories"
                                                multiple
                                                chips
                                                :error-messages="errors.category_ids"
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit" variant="flat" prepend-icon="mdi-tag-multiple">Assign Categories</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Current Categories -->
                            <v-card-text>
                                <div class="text-h6 mb-4">Current Categories</div>
                                <v-chip-group v-if="service.categories && service.categories.length > 0">
                                    <v-chip v-for="category in service.categories" :key="category.id">
                                        {{ category.name }}
                                    </v-chip>
                                </v-chip-group>
                                <div v-else class="text-center pa-4">
                                    No categories assigned yet.
                                </div>
                            </v-card-text>
                        </v-card>

                        <!-- Manage Categories -->
                        <v-card variant="text" class="mt-6">
                            <v-card-title class="text-h6">
                                Manage Service Categories
                                <v-spacer></v-spacer>
                                <v-btn color="primary" variant="flat" prepend-icon="mdi-plus" @click="editingCategory = null; categoryForm.reset()">
                                    Add New Service Category
                                </v-btn>
                            </v-card-title>

                            <!-- Category Form -->
                            <v-card-text v-if="!editingCategory">
                                <v-form @submit.prevent="addCategory">
                                    <v-row>
                                        <v-col cols="12" sm="8">
                                            <v-text-field
                                                v-model="categoryForm.name"
                                                label="Service Category Name"
                                                placeholder="e.g. Web Development"
                                                :error-messages="categoryForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-btn color="primary" type="submit" variant="flat" prepend-icon="mdi-plus">Create</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Edit Category Form -->
                            <v-card-text v-else>
                                <v-form @submit.prevent="updateCategory">
                                    <v-row>
                                        <v-col cols="12" sm="8">
                                            <v-text-field
                                                v-model="categoryForm.name"
                                                label="Service Category Name"
                                                placeholder="e.g. Web Development"
                                                :error-messages="categoryForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-btn color="primary" type="submit" class="mr-2" variant="flat" prepend-icon="mdi-content-save">Update</v-btn>
                                            <v-btn @click="cancelCategoryEdit" variant="flat" color="secondary" prepend-icon="mdi-close">Cancel</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <v-card-title class="text-h6">
                                All Service Categories
                            </v-card-title>

                            <!-- All Categories Table -->
                            <v-card-text>
                                <v-table v-if="serviceCategories && serviceCategories.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Service Category Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="category in serviceCategories" :key="category.id">
                                            <td>{{ category.name }}</td>
                                            <td>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-pencil" @click="editCategory(category)" color="primary me-2">
                                                    Edit
                                                </v-btn>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-delete" @click="deleteCategory(category)" color="error">
                                                    Delete
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                                <div v-else class="text-center pa-4">
                                    No service categories created yet.
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-window-item>
                </v-window>
            </v-card-text>
        </v-card>
    </app-layout>
</template>
