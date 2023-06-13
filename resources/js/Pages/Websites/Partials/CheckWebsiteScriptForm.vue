<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/FormSection.vue";
import ActionMessage from '@/Components/ActionMessage.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Clipboard from "@/Components/Clipboard.vue";

const props = defineProps({
    website: Object,
    script: String,
});

const form = useForm({
    check: null
});

const check = () => {
    form.post(route('websites.checks.script', props.website))
}
</script>

<template>
    <FormSection>
        <template #title>Tracking Script </template>

        <template #description>
            To include the script on your website, simply copy and paste it
            between the &lt;head&gt; tags in your site's code.
        </template>

        <template #form>
            <div class="col-span-6">
                <Clipboard :content="script"></Clipboard>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                <span class="text-green-500">The script was found on your site.</span>
            </ActionMessage>
            <ActionMessage :on="form.errors != null" class="mr-3">
                <span class="text-red-500">{{ form.errors.check }}</span>
            </ActionMessage>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="check"
            >
                Check
            </PrimaryButton>
        </template>
    </FormSection>
</template>
