<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { cn } from "@/shared/utils";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Icon } from "@/components/ui/icon";
import type { BankCard } from "@/shared/types";
import { SUPPORTED_BANKS } from "@/shared/config";
import { getSupportedBank } from "@/shared/utils";
import PaymentIcon from "./PaymentIcon.vue";

const props = defineProps<{
  class?: HTMLAttributes["class"];
  card: BankCard;
}>();

const emit = defineEmits<{
  delete: [value: string];
}>();

const formatCardNumber = (number: string): string => {
  return number.replace(/(\d{4})(?=\d)/g, "$1 ");
};

const maskCardNumber = (number: string): string => {
  const cleanNumber = number.replace(/\D/g, "");

  if (cleanNumber.length <= 10) {
    return formatCardNumber(cleanNumber);
  }

  const maskedSection = cleanNumber.slice(4, -4).replace(/\d/g, "•");

  const formattedNumber = `${cleanNumber.slice(0, 4)} ${maskedSection} ${cleanNumber.slice(-4)}`;

  return formatCardNumber(formattedNumber);
};

// const getBankName = (bank: string): string => {
//   if (bank in SUPPORTED_BANKS) {
//     const key = bank as keyof typeof SUPPORTED_BANKS;
//     return SUPPORTED_BANKS[key].name;
//   }

//   return bank;
// };
</script>

<template>
  <Card :class="cn('border shadow-sm relative group block', props.class)">
    <button
      @click="emit('delete', card._id)"
      class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 duration-150 transition-all"
    >
      <Icon name="delete" class="w-4 h-4" />
    </button>
    <CardHeader>
      <CardTitle>
        <PaymentIcon :type="card.paymentSystem" />
      </CardTitle>
      <CardDescription class="my-1">
        {{ getSupportedBank(card.bank).name }}
      </CardDescription>
    </CardHeader>
    <CardContent>
      {{ maskCardNumber(card.number) }}
    </CardContent>
    <CardFooter>
      <span class="text-sm font-medium text-muted-foreground">
        {{ card.expiryDate }}
      </span>
    </CardFooter>
  </Card>
</template>
