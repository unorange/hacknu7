import { Context } from "hono";
import { Card } from "../db/mongo";
import { deduceCreditCardType } from "../utils";

export const getUserCards = async (c: Context) => {
  const { _id: userId } = c.get("user");

  const cards = await Card.find({ userId });

  return c.json({ cards });
};

export const createCard = async (c: Context) => {
  const { _id: userId } = c.get("user");

  const { bank, month, year, number } = await c.req.json();

  const type = deduceCreditCardType(number)[0]?.type;

  if (!type) {
    c.status(400);
    return c.json({ message: "Invalid card number" });
  }

  const expiryDate = `${month}/${year}`;

  const card = await Card.create({
    bank,
    paymentSystem: type,
    number,
    expiryDate,
    userId,
  });

  if (!card) {
    c.status(400);
    return c.json({ message: "Invalid card data" });
  }

  return c.json({
    success: true,
    data: card,
    message: "Card created successfully",
  });
};

export const deleteCard = async (c: Context) => {
  const { id } = c.req.param();

  const card = await Card.findByIdAndDelete(id);

  if (!card) {
    c.status(400);
    return c.json({ message: "Card not found" });
  }

  return c.json({ success: true, message: "Card deleted successfully" });
};

export default { getUserCards, createCard, deleteCard };
