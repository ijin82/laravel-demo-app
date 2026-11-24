# syntax=docker/dockerfile:1

# ---- Frontend assets -------------------------------------------------------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json .npmrc ./
RUN npm install --ignore-scripts --no-audit --no-fund
COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

# ---- Nginx ----------------------------------------------------------------
FROM nginx:1.29-alpine-slim
RUN apk upgrade --no-cache
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY public /var/www/html/public
COPY --from=assets /app/public/build /var/www/html/public/build
