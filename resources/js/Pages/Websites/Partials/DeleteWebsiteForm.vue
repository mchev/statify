<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const confirmingWebsiteDeletion = ref(false);
const WebsiteNameInput = ref(null);

const props = defineProps({
    website: Object,
});

const form = useForm({
    name: props.website.name,
    name_confirmation: '',
});

const confirmUserDeletion = () => {
    confirmingWebsiteDeletion.value = true;

    setTimeout(() => WebsiteNameInput.value.focus(), 250);
};

const deleteWebsite = () => {
    form.delete(route('websites.destroy', props.website), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => WebsiteNameInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingWebsiteDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            Delete Website
        </template>

        <template #description>
            Permanently delete the website.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Once the website is deleted, all of its resources and data will be permanently deleted. Before deleting the website, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmUserDeletion">
                    Delete Website
                </DangerButton>
            </div>

            <!-- Delete Website Confirmation Modal -->
            <DialogModal :show="confirmingWebsiteDeletion" @close="closeModal">
                <template #title>
                    Delete Website
                </template>

                <template #content>
                    <p class="mb-2">Are you sure you want to delete the website? Once the website is deleted, all of its resources and data will be permanently deleted.</p>
                    <p>Please enter the website name ({{ website.name }}) to confirm you would like to permanently delete it.</p>

                    <div class="mt-4">
                        <TextInput
                            ref="WebsiteNameInput"
                            v-model="form.name_confirmation"
                            type="text"
                            class="mt-1 block w-3/4"
                            placeholder="Website name"
                            autocomplete="current-password"
                            @keyup.enter="deleteWebsite"
                        />

                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteWebsite"
                    >
                        Delete Website
                    </DangerButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>
