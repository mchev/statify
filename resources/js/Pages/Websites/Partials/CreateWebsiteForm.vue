<script setup>
import { useForm } from '@inertiajs/vue3';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    domain: '',
});

const createTeam = () => {
    form.post(route('websites.store'), {
        errorBag: 'createWebsite',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="createTeam">
        <template #title>
            Website Details
        </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #form>
            <div class="col-span-6">
                <InputLabel value="Current project" />

                <div class="flex items-center mt-2">
                    <div class="text-gray-900 dark:text-white">{{ $page.props.auth.user.current_team.name }}</div>
                </div>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Website Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="domain" value="Website domain" />
                <TextInput
                    id="domain"
                    v-model="form.domain"
                    type="text"
                    class="block w-full mt-1"
                />
                <InputError :message="form.errors.domain" class="mt-2" />
            </div>

        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </PrimaryButton>
        </template>
    </FormSection>
</template>
