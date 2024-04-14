export const API_URL = import.meta.env.VITE_API_URL;

export const SUPPORTED_BANKS = {
  halyk: {
    name: "Halyk Bank",
    url: "https://halykbank.kz",
    logo: "https://halykbank.kz/storage/app/uploads/public/652/cf7/01b/652cf701be5b8939869157.svg",
  },
  forte: {
    name: "Forte Bank",
    url: "https://forte.kz",
    logo: "https://main.storage-object.pscloud.io/fortebank_logotype_PNG_de167fbcfe.png",
  },
  home: {
    name: "Home Credit Bank",
    url: "https://home.kz",
    logo: "https://mybuh.kz/valuta/media/logo/homebank.svg",
  },
  eurasian: {
    name: "Eurasian Bank",
    url: "https://eubank.kz",
    logo: "https://eubank.kz/file/2023/12/2.png",
  },
};

export const SUPPORTED_CATEGORIES = {
  furniture: "Мебель",
  electronics: "Электроника",
  fuel: "Топливо",
  grocery: "Продукты питания",
  clothing: "Одежда",
  grades: "Оценки",
  banking: "Банковское дело",
  auto: "Авто",
  appliances: "Бытовая техника",
  other: "Другое",
};
