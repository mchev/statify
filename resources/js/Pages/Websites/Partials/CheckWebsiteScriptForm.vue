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
    
});

const check = () => {
    form.post('websites.check.script', props.website)
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
                Saved.
            </ActionMessage>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Check
            </PrimaryButton>
        </template>
    </FormSection>
</template>
