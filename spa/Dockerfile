FROM node:12

WORKDIR /var/www/spa
ADD . /var/www/spa

COPY package*.json ./

RUN npm install

COPY . .

EXPOSE 8080
CMD [ "node" ]

RUN npm run build
RUN rm -rf ./build
RUN rm -rf ./src

#ENV PORT=80