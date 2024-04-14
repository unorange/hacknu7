<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/components/ui/toast/use-toast";
import { Skeleton } from "@/components/ui/skeleton";
import { Separator } from "@/components/ui/separator";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { api } from "@/shared/api";
import { createAsyncProcess, isDefined } from "@/shared/utils";
import { SUPPORTED_BANKS } from "@/shared/config";
import type { BankCard } from "@/shared/types";
import CreditCard from "@/components/CreditCard.vue";

const { toast } = useToast();

const cards = ref<BankCard[]>();

const { run: getCards, loading: loadingCards } = createAsyncProcess(
  async () => {
    const [data, error] = await api.getCards();

    if (error) {
      toast({
        title: error.message,
      });
      throw error;
    }

    cards.value = data.cards;
  },
);

type UserCard = {
  bank: string;
  number: string;
  month: string;
  year: string;
};

const defaultCard: UserCard = Object.freeze({
  bank: "",
  number: "",
  month: "",
  year: "",
});

const getDefaults = () => Object.assign({}, defaultCard);

const tempCard = ref<UserCard>(getDefaults());

const resetTempCard = () => {
  Object.assign(tempCard.value, getDefaults());
};

const showBankModal = ref(false);

watch(showBankModal, (value) => {
  if (!value) {
    resetTempCard();
  }
});

const { run: addCard, loading: addingCard } = createAsyncProcess(async () => {
  if (!tempCard.value) {
    return;
  }

  const [_, error] = await api.createCart({ ...tempCard.value });

  if (error) {
    toast({
      title: error.message,
    });
    throw error;
  } else {
    console.log("1");
    showBankModal.value = false;

    await getCards();
  }
});

const { run: deleteCard, loading: deletingCard } = createAsyncProcess(
  async (id: string) => {
    const [_, error] = await api.deleteCard(id);

    if (error) {
      toast({
        title: error.message,
      });
      throw error;
    }

    await getCards();
  },
);

onMounted(getCards);
</script>

<template>
  <div>
    <h3 class="text-lg font-medium">Карты</h3>
    <p class="text-sm text-muted-foreground">Управление вашими картами</p>
  </div>
  <Separator />
  <template v-if="isDefined(cards)">
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
      <Dialog v-model:open="showBankModal">
        <DialogTrigger as-child>
          <div
            class="border rounded-lg flex items-center justify-center px-4 min-h-44"
          >
            <Button variant="outline" class="mt-4"> + </Button>
          </div>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
          <DialogHeader>
            <DialogTitle>Добавить карту</DialogTitle>
            <DialogDescription>
              Убедитесь, что вы вводите корректные данные
            </DialogDescription>
          </DialogHeader>
          <form @submit.prevent="addCard">
            <div class="grid gap-5">
              <div class="grid gap-3">
                <div class="grid gap-1.5">
                  <Label for="bank">Банк</Label>
                  <Select v-model="tempCard.bank" id="bank">
                    <SelectTrigger>
                      <SelectValue placeholder="Выберите банк" />
                    </SelectTrigger>
                    <SelectContent>
                      <template
                        v-for="[key, value] in Object.entries(SUPPORTED_BANKS)"
                        :key="key"
                      >
                        <SelectItem :value="key"> {{ value.name }} </SelectItem>
                      </template>
                    </SelectContent>
                  </Select>
                </div>

                <div class="grid gap-1.5">
                  <Label for="number">Номер карты</Label>
                  <Input
                    maxlength="23"
                    v-model="tempCard.number"
                    id="number"
                    type="text"
                    autocomplete="cc-number"
                    inputmode="numeric"
                    placeholder="•••• •••• •••• •••• •••"
                    auto-capitalize="none"
                    auto-correct="off"
                    :disabled="addingCard"
                  />
                </div>

                <div class="flex justify-between gap-1.5">
                  <div class="grid gap-1.5">
                    <Label for="month">ММ</Label>
                    <Input
                      v-model="tempCard.month"
                      id="month"
                      maxlength="2"
                      type="text"
                      autocomplete="cc-exp-month"
                      inputmode="numeric"
                      placeholder="MM"
                      auto-capitalize="none"
                      auto-correct="off"
                      :disabled="addingCard"
                    />
                  </div>
                  <div class="grid gap-1.5">
                    <Label for="year">YY</Label>
                    <Input
                      v-model="tempCard.year"
                      id="year"
                      maxlength="2"
                      type="text"
                      autocomplete="cc-exp-year"
                      inputmode="numeric"
                      placeholder="YY"
                      auto-capitalize="none"
                      auto-correct="off"
                      :disabled="addingCard"
                    />
                  </div>
                </div>
              </div>
              <Button type="submit" :disabled="addingCard"> Добавить </Button>
            </div>
          </form>
        </DialogContent>
      </Dialog>

      <CreditCard
        v-for="card in cards"
        :key="card._id"
        :card="card"
        @delete="deleteCard"
      />
    </div>
  </template>
</template>
