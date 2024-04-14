<script setup lang="ts">
import { Link } from "@/components/ui/link";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { RouterNav } from "@/components/ui/router-nav";
import { useUserStore } from "@/shared/stores/user";
import { RouteName } from "@/shared/types";
import ThemeSwitcher from "./ThemeSwitcher.vue";

const userStore = useUserStore();

function getInitials(name: string) {
  return name
    .split(" ")
    .map((n) => n[0])
    .join("");
}
</script>

<template>
  <header
    class="fixed top-0 z-[20] flex h-16 w-full flex-none justify-center bg-background border-b"
  >
    <div class="container flex h-full w-full items-center justify-between">
      <div class="inline-flex w-1/2 items-center justify-start">
        <Link :to="{ name: RouteName.Home }">
          <span class="font-bold text-xl"> CashBack </span>
        </Link>
      </div>
      <RouterNav
        :bordered="false"
        class="flex-shrink-1 hidden h-full w-full justify-center lg:flex"
      >
        <Link :to="{ name: RouteName.Home }"> Акции </Link>
        <Link :to="{ name: RouteName.Collaboration }"> Сотрудничество </Link>
      </RouterNav>
      <div class="ml-auto inline-flex w-1/2 items-center justify-end">
        <div class="flex items-center gap-3">
          <DropdownMenu v-if="userStore.user" :modal="false">
            <DropdownMenuTrigger>
              <Avatar>
                <AvatarFallback>
                  {{ getInitials(userStore.user.name) }}
                </AvatarFallback>
              </Avatar>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
              <DropdownMenuLabel>Мой Аккаунт</DropdownMenuLabel>
              <DropdownMenuSeparator />
              <RouterLink :to="{ name: RouteName.Account }">
                <DropdownMenuItem> Настройки </DropdownMenuItem>
              </RouterLink>
              <DropdownMenuItem @click="userStore.logout">
                Выйти
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
          <template v-else>
            <Link :to="{ name: RouteName.Login }">
              <Button> Войти </Button>
            </Link>
            <Link :to="{ name: RouteName.SignUp }">
              <Button variant="outline"> Создать аккаунт </Button>
            </Link>
          </template>
          <ThemeSwitcher />
        </div>
      </div>
    </div>
  </header>
</template>
