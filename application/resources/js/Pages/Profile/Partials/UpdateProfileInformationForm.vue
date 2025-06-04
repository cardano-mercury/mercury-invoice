<template>
    <v-card class="mb-6">
        <v-card-title>Profile Information</v-card-title>
        <v-card-subtitle>
            Update your account's profile information and email address.
        </v-card-subtitle>

        <v-card-text>
            <v-form @submit.prevent="updateProfileInformation">
                <div v-if="$page.props.jetstream.managesProfilePhotos">
                    <!-- Photo -->
                    <div class="mb-4">
                        <v-label>Photo</v-label>

                        <!-- Current Profile Photo -->
                        <div v-show="!photoPreview" class="mt-2">
                            <v-img
                                :src="user.profile_photo_url"
                                :alt="user.name"
                                height="80"
                                width="80"
                                class="rounded"
                            ></v-img>
                        </div>

                        <!-- New Profile Photo Preview -->
                        <div v-show="photoPreview" class="mt-2">
                            <v-img
                                :src="photoPreview"
                                height="80"
                                width="80"
                                class="rounded"
                            ></v-img>
                        </div>

                        <v-btn
                            class="mt-2 mr-2"
                            size="small"
                            @click="selectNewPhoto"
                        >
                            Select A New Photo
                        </v-btn>

                        <v-btn
                            v-if="user.profile_photo_path"
                            color="error"
                            size="small"
                            class="mt-2"
                            @click="deletePhoto"
                        >
                            Remove Photo
                        </v-btn>

                        <v-file-input
                            ref="photo"
                            v-model="form.photo"
                            style="display: none"
                            @update:model-value="updatePhotoPreview"
                        ></v-file-input>
                    </div>
                </div>

                <!-- Name -->
                <v-text-field
                    v-model="form.name"
                    label="Name"
                    :error-messages="form.errors.name"
                    required
                ></v-text-field>

                <!-- Email -->
                <v-text-field
                    v-model="form.email"
                    label="Email"
                    :error-messages="form.errors.email"
                    required
                ></v-text-field>

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Your email address is unverified.
                        <v-btn
                            variant="text"
                            color="primary"
                            class="text-decoration-underline"
                            :loading="verificationLinkSent"
                            @click.prevent="sendEmailVerification"
                        >
                            Click here to re-send the verification email.
                        </v-btn>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 text-success">
                        A new verification link has been sent to your email address.
                    </div>
                </div>

                <div class="d-flex justify-end mt-4">
                    <v-snackbar
                        v-model="showSuccessMessage"
                        color="success"
                        timeout="3000"
                    >
                        Profile information updated successfully!
                    </v-snackbar>
                    
                    <v-btn
                        color="success"
                        :loading="form.processing"
                        type="submit"
                        variant="flat"
                        prepend-icon="mdi-content-save"
                    >
                        Save
                    </v-btn>
                </div>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
import { defineComponent } from 'vue';
import { useForm } from '@inertiajs/vue3';

export default defineComponent({
    props: ['user'],

    data() {
        return {
            form: useForm({
                _method: 'PUT',
                name: this.user.name,
                email: this.user.email,
                photo: null,
            }),

            photoPreview: null,
            verificationLinkSent: false,
            showSuccessMessage: false,
        };
    },

    methods: {
        updateProfileInformation() {
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0];
            }

            this.form.post(route('user-profile-information.update'), {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: () => {
                    this.clearPhotoFileInput();
                    this.showSuccessMessage = true;
                },
            });
        },

        selectNewPhoto() {
            this.$refs.photo.$el.querySelector('input').click();
        },

        updatePhotoPreview() {
            const photo = this.$refs.photo.files[0];

            if (!photo) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };

            reader.readAsDataURL(photo);
        },

        deletePhoto() {
            this.$inertia.delete(route('current-user-photo.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.photoPreview = null;
                    this.clearPhotoFileInput();
                },
            });
        },

        clearPhotoFileInput() {
            if (this.$refs.photo?.$el.querySelector('input')) {
                this.$refs.photo.$el.querySelector('input').value = null;
            }
        },

        sendEmailVerification() {
            this.verificationLinkSent = true;

            this.$inertia.post(route('verification.send'));
        },
    },
});
</script>
