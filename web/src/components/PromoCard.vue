<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { cn } from "@/shared/utils";
import {
  Card,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import BankIcon from "./BankIcon.vue";

const props = defineProps<{
  class?: HTMLAttributes["class"];
  bank?: string;
  name: string;
  description: string;
  previewUrl: string;
  endDate: number;
}>();
</script>

<template>
  <Card :class="cn('flex flex-col relative', props.class)">
    <img
      class="w-full h-40 object-cover rounded-lg"
      :src="
        previewUrl ||
        'https://marich-ka.com.ua/wp-content/uploads/2018/03/acion.png'
      "
    />
    <BankIcon
      v-if="bank"
      :bank="bank"
      class="w-20 absolute p-2 bg-inherit right-3 bottom-3"
    />
    <CardHeader>
      <CardTitle>
        {{ name }}
      </CardTitle>
      <CardDescription class="min-h-16 line-clamp-3">
        {{ description }}
      </CardDescription>
    </CardHeader>
    <CardFooter>
      <span class="text-sm text-muted-foreground font-medium"> до 5 мая </span>
    </CardFooter>
  </Card>
</template>
