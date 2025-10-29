// import { defineConfig } from 'vite'
// import react from '@vitejs/plugin-react'

// // https://vite.dev/config/
// export default defineConfig({
//   plugins: [react()],
// })

// ...existing code...
import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      // Proxy /api to the PHP backend inside XAMPP
      "/api": {
        target: "http://localhost",
        changeOrigin: true,
        secure: false,
        // Adjust the rewrite path if your folder name differs
        rewrite: (path) =>
          path.replace(/^\/api/, "/Food-Web-App_IE4727/food-web-app/backend/api"),
      },
    },
  },
});