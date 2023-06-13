<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    website: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.website.name,
    domain: props.website.domain,
});

const updateProfileInformation = () => {
    form.post(route('websites.update', props.website), {
        errorBag: 'updateWebsiteInformation',
        preserveScroll: true,
    });
};

</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Website Information
        </template>

        <template #description>
            Update your website name and domain.
        </template>

        <template #form>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Domain -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="domain" value="Domain" />
                <TextInput
                    id="domain"
                    v-model="form.domain"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="domain"
                />
                <InputError :message="form.errors.domain" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
