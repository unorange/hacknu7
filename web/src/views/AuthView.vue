<script setup lang="ts">
import { useRoute } from "vue-router";
import { RouteName } from "@/shared/types";
import { Link } from "@/components/ui/link";
import LoginForm from "@/components/LoginForm.vue";
import SignupForm from "@/components/SignupForm.vue";

const route = useRoute();
</script>

<template>
  <div
    class="container grid h-full grid-cols-1 flex-col items-center justify-center lg:max-w-none lg:px-0"
  >
    <div class="lg:p-8">
      <div
        class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]"
      >
        <div class="flex flex-col space-y-2 text-center">
          <h1 class="text-2xl font-semibold tracking-tight">
            <template v-if="route.name === RouteName.Login">
              С возвращением
            </template>
            <template v-else> Let's go! </template>
          </h1>
          <p class="text-sm text-muted-foreground">
            <template v-if="route.name === RouteName.Login">
              Введите свои учетные данные ниже, чтобы войти
            </template>
            <template v-else> Заполните свои данные, чтобы начать </template>
          </p>
        </div>
        <template v-if="route.name === RouteName.Login">
          <LoginForm />
        </template>
        <template v-else-if="route.name === RouteName.SignUp">
          <SignupForm />
        </template>
        <p class="px-8 text-center text-sm text-muted-foreground">
          {{
            route.name === RouteName.Login
              ? "Нет аккаунта?"
              : "Уже есть аккаунт?"
          }}
          <Link
            underline
            hover
            :to="{
              name:
                route.name === RouteName.Login
                  ? RouteName.SignUp
                  : RouteName.Login,
            }"
          >
            {{ route.name === RouteName.Login ? "Пора исправлять" : "Войти" }}
          </Link>
        </p>
      </div>
    </div>
  </div>
</template>
