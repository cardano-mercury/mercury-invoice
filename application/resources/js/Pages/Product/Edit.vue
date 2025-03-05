<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router as Inertia, useForm } from '@inertiajs/vue3';

const props = defineProps({
    errors: Object,
    product: Object,
    productCategories: Array
});

// Basic product info form
const basicInfoForm = useForm(props.product);

// Active tab management
const activeTab = ref('basic');

// Category management
const selectedCategories = ref(props.product.categories?.map(cat => cat.id) || []);

function updateCategories() {
    Inertia.put(route('products.categories.update', props.product.id), {
        product_id: props.product.id,
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
    categoryForm.post(route('product-categories.store'), {
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

    categoryForm.put(route('product-categories.update', editingCategory.value.id), {
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
    if (confirm('Are you sure you want to delete this category? It will be removed from all products.')) {
        Inertia.delete(route('product-categories.destroy', category.id), {
            preserveScroll: true
        });
    }
}
</script>

<template>
    <app-layout title="Update Product">
        <template #header>
            <h1>Update Product</h1>
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
                        <v-form @submit.prevent="basicInfoForm.put(route('products.update', product.id))">
                            <v-text-field 
                                name="name" 
                                label="Product Name" 
                                placeholder="e.g. ACME Product"
                                v-model="basicInfoForm.name"
                                :error-messages="basicInfoForm.errors.name"
                                required 
                                autofocus
                            />
                            <v-text-field 
                                name="sku" 
                                label="Product SKU" 
                                placeholder="e.g. ABCD-1234"
                                v-model="basicInfoForm.sku"
                                :error-messages="basicInfoForm.errors.sku"
                                required 
                            />
                            <v-textarea 
                                name="description"
                                label="Description" 
                                placeholder=".e.g. ACME Product is our latest product..."
                                v-model="basicInfoForm.description"
                                :error-messages="basicInfoForm.errors.description"
                            />
                            <v-text-field 
                                name="unit_type" 
                                label="Unit Type" 
                                placeholder="e.g. ounce"
                                v-model="basicInfoForm.unit_type"
                                :error-messages="basicInfoForm.errors.unit_type"
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
                                label="Product Supplier" 
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
                                                :items="productCategories"
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
                                <v-chip-group v-if="product.categories && product.categories.length > 0">
                                    <v-chip v-for="category in product.categories" :key="category.id">
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
                                Manage Product Categories
                                <v-spacer></v-spacer>
                                <v-btn color="primary" variant="flat" prepend-icon="mdi-plus" @click="editingCategory = null; categoryForm.reset()">
                                    Add New Product Category
                                </v-btn>
                            </v-card-title>

                            <!-- Category Form -->
                            <v-card-text v-if="!editingCategory">
                                <v-form @submit.prevent="addCategory">
                                    <v-row>
                                        <v-col cols="12" sm="8">
                                            <v-text-field
                                                v-model="categoryForm.name"
                                                label="Product Category Name"
                                                placeholder="e.g. Electronics"
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
                                                label="Product Category Name"
                                                placeholder="e.g. Electronics"
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
                                All Product Categories
                            </v-card-title>

                            <!-- All Categories Table -->
                            <v-card-text>
                                <v-table v-if="productCategories && productCategories.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Product Category Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="category in productCategories" :key="category.id">
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
                                    No product categories created yet.
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-window-item>
                </v-window>
            </v-card-text>
        </v-card>
    </app-layout>
</template>
