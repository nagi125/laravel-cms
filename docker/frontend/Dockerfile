FROM node:24-trixie-slim

WORKDIR /app

COPY src/frontend/package.json src/frontend/package-lock.json ./

RUN npm ci

CMD ["./node_modules/.bin/next", "dev", "--hostname", "0.0.0.0"]
