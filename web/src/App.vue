<script setup lang="ts">
import { RouterView, useRoute, useRouter } from "vue-router";
import { onMounted, watch } from "vue";
import { useUserStore } from "@/shared/stores/user";
import { useAppStore } from "@/shared/stores/app";
import { RouteName } from "@/shared/types";
import Toaster from "@/components/ui/toast/Toaster.vue";
import TheHeader from "@/components/TheHeader.vue";

const appStore = useAppStore();

watch(
  () => appStore.theme,
  (value) => {
    document.documentElement.setAttribute("data-theme", value);
  },
  { immediate: true },
);

const route = useRoute();
const router = useRouter();

const userStore = useUserStore();

watch(
  () => userStore.authorized,
  (now, was) => {
    if (was && !now && route.meta.auth === "required") {
      router.push({ name: RouteName.Login, query: { next: route.fullPath } });
    }

    if (!was && now && route.meta.auth === "forbidden") {
      const redirectTo = route.query.next as string;

      if (redirectTo) {
        return router.push(redirectTo);
      }

      router.push({ name: RouteName.Home });
    }
  },
);
</script>

<template>
  <TheHeader />
  <!-- <div class="bg-secondary"> -->
  <div class="mb-10 mt-28 flex-wrap justify-between sm:flex">
    <main class="flex w-full flex-col">
      <RouterView v-slot="{ Component }">
        <!-- <KeepAlive> -->
        <Component :is="Component" />
        <!-- </KeepAlive> -->
      </RouterView>
    </main>
    <!-- </div> -->
  </div>
  <Toaster />

  <!-- <header>
    <img alt="Vue logo" class="logo" src="@/assets/logo.svg" width="125" height="125" />

    <div class="wrapper">
      <HelloWorld msg="You did it!" />

      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/about">About</RouterLink>
      </nav>
    </div>
  </header> -->

  <!-- <RouterView /> -->
</template>
