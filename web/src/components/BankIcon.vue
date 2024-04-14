<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { cn } from "@/shared/utils";
import { SUPPORTED_BANKS } from "@/shared/config";
import { getSupportedBank } from "@/shared/utils";

const props = defineProps<{
  class?: HTMLAttributes["class"];
  bank: string;
}>();
</script>

<template>
  <img
    :class="cn('w-32 h-auto', props.class)"
    :src="getSupportedBank(bank).logo"
  />
</template>
