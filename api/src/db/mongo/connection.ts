import * as mongoose from "mongoose";
import { config } from "../../config";

const connectDB = async () => {
  try {
    if (config.mongoURI !== undefined) {
      const conn = await mongoose.connect(config.mongoURI, {
        autoIndex: true,
      });

      console.log(`MongoDB Connected: ${conn.connection.host}`);
    }
  } catch (err: any) {
    console.error(`Error: ${err.message}`);
    process.exit(1);
  }
};

export default connectDB;
