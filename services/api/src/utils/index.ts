import { Jwt } from "hono/utils/jwt";
import { config } from "../config";

export { default as deduceCreditCardType } from "credit-card-type";

const genToken = (id: string) => {
  return Jwt.sign({ id }, config.jwtSecret);
};

const verifyPassword = async (
  enteredPassword: string,
  userPassword: string
) => {
  return Bun.password.verifySync(enteredPassword, userPassword);
};

const hashPassword = async (password: string) => {
  return Bun.password.hash(password, {
    algorithm: "bcrypt",
    cost: 4,
  });
};

export { genToken, verifyPassword, hashPassword };
