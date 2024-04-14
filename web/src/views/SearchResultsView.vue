<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { RouterView, useRoute } from "vue-router";
import { useUserStore } from "@/shared/stores/user";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import type { PromoAction } from "@/shared/types";
import { api } from "@/shared/api";
import {
  vFocus,
  createAsyncProcess,
  isDefined,
  getSupportedCategory,
} from "@/shared/utils";
import { SUPPORTED_BANKS, SUPPORTED_CATEGORIES } from "@/shared/config";
import RoundedSection from "@/components/RoundedSection.vue";
import PageWrapper from "@/components/PageWrapper.vue";
import PromoCard from "@/components/PromoCard.vue";
import PaymentIcon from "@/components/PaymentIcon.vue";
import BankIcon from "@/components/BankIcon.vue";

const userStore = useUserStore();

const route = useRoute();

const query = route.query.q as string;

const results = ref<PromoAction[]>();

const { run: load, loading } = createAsyncProcess(async () => {
  const [data, error] = await api.search({
    query,
  });

  if (error) {
    console.error(error);
    throw error;
  }

  results.value = data.data;

  console.log(data, error);
});

onMounted(async () => {
  await load();
});

const showPromoModal = ref(false);

const tempPromo = ref<PromoAction>();

const resetTempPromo = () => {
  tempPromo.value = undefined;
};

const setTempPromo = (promo: PromoAction) => {
  tempPromo.value = promo;
};

watch(showPromoModal, (value) => {
  if (!value) {
    resetTempPromo();
  }
});
</script>

<template>
  <PageWrapper>
    <template #title>
      <h1>Results for {{ query }}</h1>
    </template>
    <RoundedSection>
      <div
        class="flex flex-col space-y-8 container py-4 lg:flex-row lg:space-x-12 lg:space-y-0"
      >
        <aside class="-mx-4 lg:w-1/5">
          <div class="grid gap-5">
            <div class="grid gap-1.5">
              <Label for="bank">Банк</Label>
              <Select id="bank">
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
              <Label for="bank">Категория</Label>
              <Select id="bank">
                <SelectTrigger>
                  <SelectValue placeholder="Выберите категорию" />
                </SelectTrigger>
                <SelectContent>
                  <template
                    v-for="[key, value] in Object.entries(SUPPORTED_CATEGORIES)"
                    :key="key"
                  >
                    <SelectItem :value="key"> {{ value }} </SelectItem>
                  </template>
                </SelectContent>
              </Select>
            </div>
          </div>
        </aside>
        <Separator orientation="vertical" />
        <div class="flex-1">
          <div class="space-y-6">
            <section
              class="grid grid-cols-1 lg:grid-cols-4 md:grid-cols-2 justify-items-center justify-center gap-y-6 gap-x-6"
            >
              <Dialog v-model:open="showPromoModal">
                <DialogContent
                  v-if="isDefined(tempPromo)"
                  class="sm:max-w-2xl grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh]"
                >
                  <DialogHeader class="p-6 pb-0">
                    <DialogTitle>
                      {{ tempPromo.title }}
                    </DialogTitle>
                    <DialogDescription>
                      <div class="flex items-center gap-4 pt-6">
                        <BankIcon :bank="tempPromo.bank_type.bank" />
                        <div class="flex gap-2 items-center">
                          <template
                            v-for="item in tempPromo.bank_type.payment_systems"
                            :key="item"
                          >
                            <PaymentIcon :type="item" />
                          </template>
                        </div>
                      </div>
                    </DialogDescription>
                  </DialogHeader>
                  <div class="grid gap-4 py-4 overflow-y-auto px-6">
                    <!-- <div class="flex flex-col justify-between"> -->
                    <div class="flex flex-col gap-1">
                      <template v-if="tempPromo.cashback">
                        <p>
                          <strong>Кэшбек:</strong> {{ tempPromo.cashback }}%
                        </p>
                      </template>
                      <p v-if="tempPromo.condition">
                        <strong>Условия:</strong> {{ tempPromo.condition }}
                      </p>
                      <p v-if="tempPromo.limitation">
                        <strong>Ограничения:</strong> {{ tempPromo.limitation }}
                      </p>
                      <p v-if="tempPromo.time_end">
                        <strong>До:</strong> {{ tempPromo.time_end }}
                      </p>
                      <p v-if="tempPromo.category">
                        <strong>Категория:</strong>
                        {{ getSupportedCategory(tempPromo.category) }}
                      </p>
                      <p v-if="tempPromo.city">
                        <strong>Город:</strong>
                        {{ tempPromo.city }}
                      </p>
                    </div>

                    <div v-if="tempPromo.raw" v-html="tempPromo.raw" />

                    <!-- {{ tempPromo }} -->
                  </div>
                  <!-- </div> -->
                  <!-- <DialogFooter class="p-6 pt-0">
                    <Button type="submit"> Save changes </Button>
                  </DialogFooter> -->
                </DialogContent>
              </Dialog>
              <template v-if="loading">
                <template v-for="i in 4" :key="i">
                  <Skeleton class="h-48 w-full" />
                </template>
              </template>
              <template v-else>
                <template v-if="!results?.length">
                  <p>Ничего не найдено</p>
                </template>
                <template v-else>
                  <PromoCard
                    v-for="item in results"
                    :key="item.url"
                    @click="
                      () => {
                        setTempPromo(item);
                        showPromoModal = true;
                      }
                    "
                    class="border w-full hover:cursor-pointer"
                    :name="item.title"
                    :description="item.condition"
                    :preview-url="item.image_url"
                    :end-date="item.time_end"
                    :bank="item.bank_type.bank"
                  />
                </template>
              </template>
            </section>
          </div>
        </div>
      </div>
    </RoundedSection>
  </PageWrapper>
</template>
