import { Document, Schema, model } from "mongoose";

interface ICard {
  bank: string;
  paymentSystem: string;
  number: string;
  expiryDate: string;
  userId: string;
}

interface ICardDoc extends ICard, Document {}

const cardSchema = new Schema<ICardDoc>(
  {
    bank: { type: String, required: true },
    paymentSystem: { type: String, required: true },
    number: { type: String, required: true },
    expiryDate: { type: String, required: true },
    userId: { type: String, ref: "User", required: true },
  },
  {
    timestamps: true,
  }
);

const Card = model("Card", cardSchema);

export default Card;
