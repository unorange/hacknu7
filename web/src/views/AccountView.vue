<script setup lang="ts">
import { RouterView } from "vue-router";
import { useUserStore } from "@/shared/stores/user";
import RoundedSection from "@/components/RoundedSection.vue";
import PageWrapper from "@/components/PageWrapper.vue";
import SidebarNav from "@/components/AccountSidebar.vue";

const userStore = useUserStore();
</script>

<template>
  <PageWrapper v-if="userStore.user && userStore.authorized">
    <template #title>
      <h1>Аккаунт</h1>
    </template>
    <RoundedSection>
      <div
        class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0"
      >
        <aside class="-mx-4 lg:w-1/5">
          <SidebarNav />
        </aside>
        <div class="flex-1">
          <div class="space-y-6">
            <RouterView />
            <!-- <slot /> -->
          </div>
        </div>
      </div>
    </RoundedSection>
  </PageWrapper>
</template>
