import { ref, computed } from "vue";
import { defineStore } from "pinia";
import { useStorage, StorageSerializers } from "@vueuse/core";
import type { RemovableRef } from "@vueuse/core";
import { getStorageKey } from "@/shared/utils";
import type { User } from "@/shared/types";

export const useUserStore = defineStore("user", () => {
  const token: RemovableRef<string> = useStorage(getStorageKey("token"), "");

  const user: RemovableRef<User | undefined> = useStorage(
    getStorageKey("user"),
    null,
    undefined,
    {
      serializer: StorageSerializers.object,
    },
  );

  const authorized = computed(() => {
    return !!user.value && !!token.value;
  });

  const setToken = (newToken: string) => {
    token.value = newToken;
  };

  const login = (tokenData: string, userData: User) => {
    setToken(tokenData);
    user.value = {
      _id: userData._id,
      name: userData.name,
      email: userData.email,
    };
  };

  const logout = () => {
    token.value = "";
    user.value = null;
  };

  return {
    user,
    token,
    setToken,
    authorized,
    login,
    logout,
  };
});
