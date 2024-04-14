/// Routing

export enum RouteName {
  Home = "home",
  Login = "login",
  SignUp = "sign-up",
  NotFound = "404",
  Account = "account",
  AccountProfile = "profile",
  AccountCards = "cards",
  Search = "search",
  Collaboration = "collab",
}

/// HTTP

export type APIError = {
  status: number;
  message: string;
};

export type APIErrorResponse = {
  error: APIError;
};

export type APIWrapper<T> = T | APIErrorResponse;

/// Application

export type User = {
  _id: string;
  name: string;
  email: string;
};

/// API

export type BankCard = {
  _id: string;
  bank: string;
  paymentSystem: string;
  number: string;
  expiryDate: string;
  userId: string;
  createdAt: string;
  updatedAt: string;
  __v: number;
};

export type PromoAction = {
  hash: string;
  bank_type: {
    bank: string;
    payment_systems: string[];
    card_type: null | string;
  };
  cashback: number | null;
  raw: string;
  title: string;
  url: string;
  image_url: string;
  limitation: string;
  condition: string;
  category: null | string;
  franchise: null | string;
  city: null | string;
  created_at: number;
  time_end: number;
  time_start: number;
};
