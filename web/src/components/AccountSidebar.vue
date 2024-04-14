<script setup lang="ts">
import { useRoute } from "vue-router";
import { cn } from "@/shared/utils";
import { Button } from "@/components/ui/button";
import { Link } from "./ui/link";
import { RouteName } from "@/shared/types";

interface Item {
  title: string;
  route: string;
}

const route = useRoute();

const sidebarNavItems: Item[] = [
  {
    title: "Профиль",
    route: RouteName.AccountProfile,
  },
  {
    title: "Карты",
    route: RouteName.AccountCards,
  },
];
</script>

<template>
  <nav class="flex space-x-2 lg:flex-col lg:space-x-0 lg:space-y-1">
    <Link
      v-for="item in sidebarNavItems"
      :key="item.title"
      :to="{ name: item.route }"
    >
      <Button
        variant="ghost"
        :class="
          cn(
            'w-full text-left justify-start',
            route.name === item.route && 'bg-muted hover:bg-muted',
          )
        "
      >
        {{ item.title }}
      </Button>
    </Link>
  </nav>
</template>
