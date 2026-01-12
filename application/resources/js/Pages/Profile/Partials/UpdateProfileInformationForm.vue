<template>
    <v-card rounded="lg">
        <v-card-title class="d-flex align-center">
            <v-icon icon="mdi-account-circle" color="primary" class="mr-2" />
            Profile Information
        </v-card-title>
        <v-card-subtitle>
            Update your account's profile information and email address.
        </v-card-subtitle>

        <v-card-text>
            <v-form @submit.prevent="updateProfileInformation">
                <div v-if="$page.props.jetstream.managesProfilePhotos" class="mb-6">
                    <div class="text-subtitle-2 font-weight-medium mb-2">Profile Photo</div>
                    
                    <div class="d-flex align-center ga-4">
                        <!-- Current Profile Photo -->
                        <v-avatar v-show="!photoPreview" size="80" rounded="lg">
                            <v-img :src="user.profile_photo_url" :alt="user.name" />
                        </v-avatar>

                        <!-- New Profile Photo Preview -->
                        <v-avatar v-show="photoPreview" size="80" rounded="lg">
                            <v-img :src="photoPreview" />
                        </v-avatar>

                        <div>
                            <v-btn
                                variant="tonal"
                                color="primary"
                                size="small"
                                prepend-icon="mdi-camera"
                                @click="selectNewPhoto"
                                class="mr-2"
                            >
                                Change Photo
                            </v-btn>

                            <v-btn
                                v-if="user.profile_photo_path"
                                color="error"
                                variant="text"
                                size="small"
                                prepend-icon="mdi-trash-can"
                                @click="deletePhoto"
                            >
                                Remove
                            </v-btn>
                        </div>
                    </div>

                    <v-file-input
                        ref="photo"
                        v-model="form.photo"
                        style="display: none"
                        @update:model-value="updatePhotoPreview"
                    />
                </div>

                <!-- Name -->
                <v-text-field
                    v-model="form.name"
                    label="Full Name"
                    placeholder="John Doe"
                    prepend-inner-icon="mdi-account"
                    :error-messages="form.errors.name"
                    variant="outlined"
                    density="comfortable"
                    required
                />

                <!-- Email -->
                <v-text-field
                    v-model="form.email"
                    label="Email Address"
                    placeholder="you@example.com"
                    prepend-inner-icon="mdi-email"
                    :error-messages="form.errors.email"
                    variant="outlined"
                    density="comfortable"
                    required
                />

                <v-alert
                    v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null"
                    type="warning"
                    variant="tonal"
                    density="compact"
                    class="mt-4"
                >
                    <div class="d-flex align-center flex-wrap">
                        <span>Your email address is unverified.</span>
                        <v-btn
                            variant="text"
                            color="warning"
                            size="small"
                            :loading="verificationLinkSent"
                            @click.prevent="sendEmailVerification"
                            class="ml-2"
                        >
                            Resend verification email
                        </v-btn>
                    </div>
                </v-alert>

                <v-alert
                    v-if="verificationLinkSent"
                    type="success"
                    variant="tonal"
                    density="compact"
                    class="mt-2"
                >
                    A new verification link has been sent to your email address.
                </v-alert>

                <div class="d-flex justify-end mt-6">
                    <v-snackbar
                        v-model="showSuccessMessage"
                        color="success"
                        timeout="3000"
                    >
                        Profile information updated successfully!
                    </v-snackbar>
                    
                    <v-btn
                        color="primary"
                        :loading="form.processing"
                        type="submit"
                        variant="flat"
                        prepend-icon="mdi-content-save"
                    >
                        Save Changes
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
