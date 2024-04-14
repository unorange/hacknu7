<script setup lang="ts">
import { ref } from "vue";
import { cn } from "@/shared/utils";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/components/ui/toast/use-toast";
import { api } from "@/shared/api";
import { createAsyncProcess, vFocus } from "@/shared/utils";
import { useUserStore } from "@/shared/stores/user";
import { RouteName } from "@/shared/types";

const userStore = useUserStore();

const form = ref({
  email: "",
  password: "",
});

const { toast } = useToast();

const { run: submit, loading } = createAsyncProcess(async () => {
  const [data, error] = await api.login({
    email: form.value.email,
    password: form.value.password,
  });

  console.log(data, error);

  if (error) {
    toast({
      title: error.message,
    });
    throw error;
  }

  userStore.login(data.token, data.data);
});
</script>

<template>
  <div :class="cn('grid gap-6', $attrs.class ?? '')">
    <form @submit.prevent="submit">
      <div class="grid gap-5">
        <div class="grid gap-3">
          <div class="grid gap-1.5">
            <Label for="email">Email</Label>
            <Input
              v-focus
              v-model="form.email"
              placeholder="name@example.com"
              id="email"
              type="email"
              autocomplete="email"
              auto-capitalize="none"
              auto-correct="off"
              :disabled="loading"
            />
          </div>
          <div class="grid gap-1.5">
            <Label for="password">Пароль</Label>
            <Input
              v-model="form.password"
              placeholder="123123123"
              id="password"
              type="password"
              auto-capitalize="none"
              auto-correct="off"
              :disabled="loading"
              required
            />
          </div>
        </div>
        <Button :disabled="loading"> Войти </Button>
      </div>
    </form>
  </div>
</template>
