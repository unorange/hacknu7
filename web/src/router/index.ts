import { createRouter, createWebHistory } from "vue-router";
import { RouteName } from "@/shared/types";
import { useUserStore } from "@/shared/stores/user";
import HomeView from "@/views/HomeView.vue";
import AuthView from "@/views/AuthView.vue";
import AccountView from "@/views/AccountView.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import AccountProfileView from "@/views/AccountProfileView.vue";
import AccountCardsView from "@/views/AccountCardsView.vue";
import SearchResultsView from "@/views/SearchResultsView.vue";
import CollaborationView from "@/views/CollaborationView.vue";

declare module "vue-router" {
  interface RouteMeta {
    auth: "required" | "forbidden" | "none";
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/auth/login",
      name: RouteName.Login,
      meta: { auth: "forbidden" },
      component: AuthView,
    },
    {
      path: "/auth/signup",
      name: RouteName.SignUp,
      meta: { auth: "forbidden" },
      component: AuthView,
    },
    {
      path: "/account",
      name: RouteName.Account,
      meta: { auth: "required" },
      redirect: { name: RouteName.AccountProfile },
      component: AccountView,
      children: [
        {
          path: "",
          name: RouteName.AccountProfile,
          component: AccountProfileView,
        },
        {
          path: "cards",
          name: RouteName.AccountCards,
          component: AccountCardsView,
        },
      ],
    },
    {
      path: "/search",
      name: RouteName.Search,
      component: SearchResultsView,
    },
    {
      path: "/collaboration",
      name: RouteName.Collaboration,
      component: CollaborationView,
    },
    {
      path: "/:pathMatch(.*)*",
      name: RouteName.NotFound,
      component: NotFoundView,
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return new Promise((resolve) => {
        setTimeout(() => {
          resolve({ el: to.hash, top: 80 });
        }, 150);
      });
    }

    if (savedPosition) {
      return savedPosition;
    }

    if (
      to.name === from.name &&
      JSON.stringify(to.query) !== JSON.stringify(from.query)
    ) {
      return;
    }

    return { top: 0 };
  },
});

router.beforeEach((to) => {
  const userStore = useUserStore();

  const authorized = userStore.authorized;

  if (to.meta.auth === "required" && !authorized) {
    return { name: RouteName.Login, query: { next: to.fullPath } };
  }

  if (to.meta.auth === "forbidden" && authorized) {
    return { name: RouteName.Home };
  }
});

export default router;
