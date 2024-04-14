<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { useRouter } from "vue-router";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Link } from "@/components/ui/link";
import { ToggleGroup, ToggleGroupItem } from "@/components/ui/toggle-group";
import type { BankCard } from "@/shared/types";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";
import { api } from "@/shared/api";
import { vFocus, createAsyncProcess } from "@/shared/utils";
import { SUPPORTED_BANKS } from "@/shared/config";
import { RouteName } from "@/shared/types";
import { useUserStore } from "@/shared/stores/user";
import PromoCard from "@/components/PromoCard.vue";

const toggledBanks = ref<string[]>();

const searchQuery = ref("");

const router = useRouter();

const search = async () => {
  await router.push({
    name: RouteName.Search,
    query: {
      q: searchQuery.value,
      ...((toggledBanks.value ?? []).length && {
        banks: (toggledBanks.value ?? []).join(","),
      }),
    },
  });
};

const cards = ref<BankCard[]>();

const { run: getCards, loading: loadingCards } = createAsyncProcess(
  async () => {
    const [data, error] = await api.getCards();

    if (error) {
      // toast({
      //   title: error.message,
      // });
      throw error;
    }

    cards.value = data.cards;
  },
);

// const { run: load, loading } = createAsyncProcess(async () => {
//   // await api.search('search')
// });

const userStore = useUserStore();

// watch(
//   () => userStore.authorized,
//   async (newValue) => {
//     if (newValue) {
//       await getCards();

//       toggledBanks.value = cards.value?.map((card) => card.bank) || [];
//     }
//   },
// );

onMounted(async () => {
  if (userStore.authorized) {
    await getCards();
    toggledBanks.value = cards.value?.map((card) => card.bank) || [];
  } else {
    toggledBanks.value = Object.keys(SUPPORTED_BANKS);
  }

  // if(!isD)
  // load();
});

const contents = [
  {
    name: "Halyk Bank",
    description: "20% бонусов в магазине Sulpak на продукцию Haier и Candy",
    previewUrl:
      "https://halykbank.kz/storage/app/uploads/public/660/f7e/e8d/660f7ee8dd14c633891532.png",
    endDate: 1713037978,
  },
  {
    name: "Jysan",
    description: "Продукты по акции и 10% Бонусов в подарок!",
    previewUrl:
      "https://jmart.kz/images/detailed/6883/image-new-admin-660b90806d7451.37376670.png",
    endDate: 1713037978,
  },
  {
    name: "Halyk Bank",
    description: "10% бонусов в сети магазинов Mechta.kz",
    previewUrl:
      "https://halykbank.kz/storage/app/uploads/public/65e/1c8/d63/65e1c8d632e2d744604434.png",
    endDate: 1713037978,
  },
  {
    name: "Jysan",
    description: "Продукты по акции и 10% Бонусов в подарок!",
    previewUrl:
      "https://jmart.kz/images/detailed/6883/image-new-admin-660b90806d7451.37376670.png",
    endDate: 1713037978,
  },
];
</script>

<template>
  <div class="py-5"></div>
  <main class="container mx-auto">
    <div class="items-center flex justify-center">
      <form @submit.prevent="search()">
        <div class="text-center gap-3.5 flex flex-col">
          <div class="items-center justify-center flex flex-wrap gap-1">
            <Badge> c нами выгодно </Badge>
          </div>
          <h1 class="text-4xl font-semibold">
            Покупайте по максимально выгодным ценам
          </h1>
          <p class="text-lg">Найдите подходящие для вас промокоды и акции</p>
          <div class="py-1"></div>
          <Input
            v-focus
            placeholder="Название магазина или товара"
            v-model="searchQuery"
            class="w-4/5 self-center"
            type="search"
          />
          <ToggleGroup
            class="flex items-start flex-wrap justify-center"
            v-model="toggledBanks"
            type="multiple"
            size="sm"
            variant="outline"
          >
            <ToggleGroupItem
              v-for="[key, value] in Object.entries(SUPPORTED_BANKS)"
              :key="key"
              :value="key"
              :aria-label="`Toggle ${value.name}`"
            >
              {{ value.name }}
            </ToggleGroupItem>
          </ToggleGroup>
          <!-- <span class="text-sm text-muted-foreground">
            бесплатно, <s>без регистрации</s> и смс
          </span> -->
        </div>
      </form>
    </div>
  </main>
  <div class="py-12"></div>
  <div
    class="py-12 bg-secondary flex items-center justify-center rounded-3xl md:mx-6 mx-2"
  >
    <div class="flex flex-col gap-1 items-center justify-center">
      <h1 class="font-medium text-3xl">Актуальные акции от банков</h1>
      <p class="text-muted-foreground">
        Найдите подходящие для вас промокоды и акции
      </p>
      <div class="py-4"></div>
      <div class="flex flex-wrap gap-3 items-center justify-center w-full">
        <Carousel
          class="relative w-full max-w-3xl"
          :opts="{
            align: 'start',
          }"
        >
          <CarouselContent>
            <CarouselItem
              v-for="(item, index) in contents"
              :key="index"
              class="md:basis-1/2 lg:basis-1/3"
            >
              <PromoCard
                :name="item.name"
                :description="item.description"
                :preview-url="item.previewUrl"
                :end-date="item.endDate"
              />
            </CarouselItem>
          </CarouselContent>
          <CarouselPrevious />
          <CarouselNext />
        </Carousel>
      </div>
      <div class="py-3"></div>
      <template v-if="!userStore.authorized">
        <Link :to="{ name: RouteName.Login }">
          <Button> Получить доступ </Button>
        </Link>
      </template>
    </div>
  </div>
</template>
