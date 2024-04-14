import { Context } from "hono";
import { config } from "../config";

const headers = {
  Accept: "application/json",
  "Content-Type": "application/json",
  "bypass-tunnel-reminder": "Hello!",
};

export const searchFullText = async (c: Context) => {
  const { query, bank = undefined, category = undefined } = await c.req.json();

  const url = `${config.scraperURL}/api/search-full-text?force=yes`;

  const response = await fetch(url, {
    method: "POST",
    body: JSON.stringify({ query: query }),
    headers,
  });

  const body = await response.json();

  return c.json(body);
};

export default {
  searchFullText,
};
