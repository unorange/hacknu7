<script setup lang="ts">
import { useUserStore } from "@/shared/stores/user";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";

const userStore = useUserStore();
</script>

<template>
  <div>
    <h3 class="text-lg font-medium">Профиль</h3>
    <p class="text-sm text-muted-foreground">Информация о вашем аккаунте</p>
  </div>
  <Separator />
  <div v-if="userStore.user">
    <div class="grid gap-3">
      <div class="grid gap-1.5">
        <Label for="password">Имя</Label>
        <Input
          v-model="userStore.user.name"
          id="name"
          type="string"
          auto-capitalize="none"
          auto-correct="off"
          :disabled="true"
          required
        />
      </div>

      <div class="grid gap-1.5">
        <Label for="email">Email</Label>
        <Input
          v-model="userStore.user.email"
          id="email"
          type="email"
          autocomplete="email"
          auto-capitalize="none"
          auto-correct="off"
          :disabled="true"
        />
      </div>
    </div>
  </div>
</template>
