<script setup lang="ts">
import { ref } from "vue";
import { cn, isEmptyString } from "@/shared/utils";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/components/ui/toast/use-toast";
import { api } from "@/shared/api";
import { createAsyncProcess, vFocus } from "@/shared/utils";
import { useUserStore } from "@/shared/stores/user";

const userStore = useUserStore();

const form = ref({
  email: "",
  name: "",
  password: "",
});

const { toast } = useToast();

const { run: submit, loading } = createAsyncProcess(async () => {
  const [data, error] = await api.register({
    name: form.value.name,
    email: form.value.email,
    password: form.value.password,
  });

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
          <!-- <div class="grid gap-1.5">
            <Label for="token"> Moodle Token </Label>
            <Input
              v-focus
              v-model="form.token"
              placeholder="5f7a16ff7204ecb9bcd16bf0125d79d9"
              id="token"
              type="password"
              auto-capitalize="none"
              auto-correct="off"
              :disabled="loading"
              required
            />
            <span class="text-sm text-muted-foreground"> Where to find a Moodle Token? </span>
          </div> -->
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
            <Label for="name">Имя</Label>
            <Input
              v-model="form.name"
              placeholder="Иван Иванович"
              id="name"
              type="text"
              autocomplete="surname"
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
            />
          </div>
        </div>
        <Button
          :disabled="
            loading ||
            isEmptyString(form.name) ||
            isEmptyString(form.name) ||
            isEmptyString(form.password)
          "
        >
          Зарегистрироваться
        </Button>
      </div>
    </form>
  </div>
</template>
