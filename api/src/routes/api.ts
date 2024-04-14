import { Hono } from "hono";
import user from "../controllers/user";
import card from "../controllers/card";
import promo from "../controllers/promo";
import { protect } from "../middlewares";

const api = new Hono();

api.post("/users", (c) => user.createUser(c));

api.post("/users/login", (c) => user.loginUser(c));

api.post("/cards", protect, (c) => card.createCard(c));

api.get("/cards", protect, (c) => card.getUserCards(c));

api.delete("/cards/:id", protect, (c) => card.deleteCard(c));

api.post("/search", (c) => promo.searchFullText(c));

export default api;
