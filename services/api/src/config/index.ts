export const config = {
  port: Bun.env.PORT || 8000,
  mongoURI: Bun.env.MONGO_URI,
  jwtSecret: Bun.env.JWT_SECRET || "",
  scraperURL: Bun.env.SCRAPER_URL || "",
};
