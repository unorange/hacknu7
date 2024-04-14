import { Context } from "hono";
import { User } from "../db/mongo";
import { genToken } from "../utils";

export const getUsers = async (c: Context) => {
  const users = await User.find();

  return c.json({ users });
};

export const createUser = async (c: Context) => {
  const { name, email, password } = await c.req.json();

  const userExists = await User.findOne({ email });
  if (userExists) {
    c.status(400);
    return c.json({ message: "User already exists" });
  }

  const user = await User.create({
    name,
    email,
    password,
  });

  if (!user) {
    c.status(400);
    return c.json({ message: "Invalid user data" });
  }

  const token = await genToken(user._id.toString());

  return c.json({
    success: true,
    data: {
      _id: user._id,
      name: user.name,
      email: user.email,
    },
    token,
    message: "User created successfully",
  });
};

export const loginUser = async (c: Context) => {
  const { email, password } = await c.req.json();

  if (!email || !password) {
    c.status(400);
    return c.json({ message: "Please provide an email and password" });
  }

  const user = await User.findOne({ email });
  if (!user) {
    c.status(401);
    return c.json({ message: "No user found with this email" });
  }

  if (!(await user.verifyPassword(password))) {
    c.status(401);
    throw new Error("Invalid credentials");
  } else {
    const token = await genToken(user._id.toString());

    return c.json({
      success: true,
      data: {
        _id: user._id,
        name: user.name,
        email: user.email,
      },
      token,
      message: "User logged in successfully",
    });
  }
};

export default {
  getUsers,
  createUser,
  loginUser,
};
