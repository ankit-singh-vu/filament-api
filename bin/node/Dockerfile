FROM node

LABEL maintainer="convesio"

RUN apt-get update && \
    apt-get -qy full-upgrade && \
    apt-get install -qy curl && \
    apt-get install -qy curl && \
    curl -fsSL https://get.docker.com | sh


RUN apt-get update

RUN apt-get -y install curl gnupg

RUN apt-get -y install nodejs

RUN apt-get install pass gnupg2 -y


WORKDIR /usr/src/app

# COPY app.js /usr/src/app/

COPY package*.json /usr/src/app/


RUN npm install

COPY . .


CMD ["npm","start"]