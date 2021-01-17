<template>
        <jet-form-section @submitted="updateLogoSetting">
            <template #title>
                Application Logo
            </template>

            <template #form >
                <div class="col-span-6 sm:col-span-4">
                    <!-- Hidden File Input -->
                    <input type="file" class="hidden" ref="logo" @change="updateLogoPreview">

                    <jet-label for="logo" value="Application Logo"/>


                    <!-- Current Logo -->
                    <div class="my-3" v-show="!preview">
                        <application-mark class="rounded-full h-40 w-40 object-cover" />
                    </div>

                    <!-- New Logo Preview -->
                    <div class="my-3" v-show="preview">
                    <span class="block rounded-full w-40 h-40"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + preview + '\');'">
                    </span>
                    </div>

                    <jet-secondary-button class="mt-2 mr-2" type="button" @click.native.prevent="selectNewLogo">
                        Select A New Logo
                    </jet-secondary-button>

<!--                    <jet-secondary-button type="button" class="mt-2" @click.native.prevent="deleteLogo" v-if="form.app_path">
                        Remove Logo
                    </jet-secondary-button>-->

                    <jet-input-error :message="form.errors.app_logo" class="mt-2" />

                </div>
            </template>

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Updated.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update
                </jet-button>
            </template>
        </jet-form-section>
</template>

<script>
import JetFormSection from '@src/Jetstream/FormSection'
import JetLabel from '@src/Jetstream/Label'
import JetInput from '@src/Jetstream/Input'
import JetInputError from '@src/Jetstream/InputError'
import JetButton from '@src/Jetstream/Button'
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetActionMessage from '@src/Jetstream/ActionMessage'
import ApplicationMark from "@src/Jetstream/ApplicationMark"

export default {
    components: {
        ApplicationMark,
        JetFormSection,
        JetLabel,
        JetInput,
        JetInputError,
        JetButton,
        JetSecondaryButton,
        JetActionMessage,
    },
    data() {
        return {
            form: this.$inertia.form({
                label: 'app_logo',
                app_logo: null,
            }),

            preview: null,
        }
    },
    methods: {
        updateLogoSetting() {
            if (this.$refs.logo) {
                this.form.app_logo = this.$refs.logo.files[0]
            }

            this.form.post('/settings/app_logo')
        },

        selectNewLogo() {
            this.$refs.logo.click();
        },

        updateLogoPreview() {
            const reader = new FileReader();

            reader.readAsDataURL(this.$refs.logo.files[0]);

            reader.onload = (e) => {
                this.preview = e.target.result;
            };
        },
    },
}
</script>
