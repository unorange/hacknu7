import ky, { HTTPError, type Options } from "ky";
import type {
  APIError,
  APIWrapper,
  BankCard,
  PromoAction,
  User,
} from "@/shared/types";
import { isDefined, isEmptyString } from "@/shared/utils";
import { useUserStore } from "@/shared/stores/user";
import { useAppStore } from "@/shared/stores/app";
import { API_URL } from "../config";

class API {
  kyInstance;

  constructor() {
    this.kyInstance = ky.create({
      // prefixUrl: import.meta.env.VITE_API_URL,
      prefixUrl: `${API_URL}/v1`,
      retry: { limit: 1 },
      hooks: {
        beforeRequest: [
          (request) => {
            const userStore = useUserStore();
            const token = userStore.token;

            if (isDefined(token) && !isEmptyString(token)) {
              request.headers.set("Authorization", `Bearer ${token}`);
            }

            request.headers.set("Connection", "keep-alive");
          },
        ],
        afterResponse: [
          (_input, _options, response) => {
            if (response.status === 401) {
              const userStore = useUserStore();

              userStore.logout();
            }
          },
        ],
      },
    });
  }

  private async request<T>(
    input: RequestInfo,
    options?: Options,
  ): Promise<[T, null] | [null, APIError]> {
    try {
      const response = await this.kyInstance(input, options).json<
        APIWrapper<T>
      >();

      return [response as T, null];
    } catch (err) {
      try {
        console.log(err);
        if (err instanceof HTTPError) {
          const response = await err.response.json();

          if ("message" in response) {
            return [
              null,
              {
                status: err.response.status,
                message: response.message,
              },
            ];
          }
        }
      } catch (_) {
        return [null, { status: 500, message: "Couldn't parse error" }];
      }

      return [null, { status: 500, message: "Something went wrong" }];
    }
  }

  async register(payload: { name: string; email: string; password: string }) {
    return this.request<{
      success: boolean;
      data: User;
      token: string;
      message: string;
    }>("users", {
      method: "POST",
      json: payload,
    });
  }

  async login(payload: { email: string; password: string }) {
    return this.request<{
      success: boolean;
      data: User;
      token: string;
      message: string;
    }>("users/login", {
      method: "POST",
      json: payload,
    });
  }

  async createCart(payload: {
    bank: string;
    number: string;
    month: string;
    year: string;
  }) {
    return this.request<{
      success: boolean;
      data: BankCard;
      message: string;
    }>("cards", {
      method: "POST",
      json: payload,
    });
  }

  async deleteCard(id: string) {
    return this.request<{
      success: boolean;
      message: string;
    }>(`cards/${id}`, {
      method: "DELETE",
    });
  }

  async getCards() {
    return this.request<{ cards: BankCard[] }>("cards", {
      method: "GET",
    });
  }

  async search(payload: { query: string; bank?: string }) {
    return this.request<{
      hits: number;
      data: PromoAction[];
    }>("search", {
      method: "POST",
      json: payload,
    });
  }
}

export const api = new API();
