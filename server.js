import express from 'express';
import path from 'path';
import {handler} from "./svelte/build/handler.js"

const app = express();
const port = "https://data-nata.vercel.app";

// demo route
app.get('/expressroute', (req, res) => {
  res.send("This is an express route");  
});

// This will connect your svelte kit app to your express server
app.use(handler)

app.listen(port, () => {
  console.log(`Server listening on port ${port}`);
}); //
