FROM php:7.1.20-cli
MAINTAINER bugbear <mulberry10th@gmail.com>
ADD sources.list /etc/apt/
RUN apt-get update && apt-get install -y curl git wget
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN rm -f composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
ENV APP_DIR=/usr/webapp
WORKDIR $APP_DIR
RUN wget https://github.com/swoole/swoole-src/archive/v1.9.13.tar.gz
RUN tar -zxvf v1.9.13.tar.gz
WORKDIR  $APP_DIR/swoole-src-1.9.13
RUN phpize
RUN ./configure
RUN make && make install
RUN rm -rf $APP_DIR/swoole-src-1.9.13
RUN echo "extension=swoole.so" >> /usr/local/etc/php/conf.d/swoole.ini
WORKDIR $APP_DIR
WORKDIR $APP_DIR/knight
# install node 
# https://github.com/nodejs/docker-node/blob/65ba769f403f8c188d9be0b1ffb8a9cfc31bf703/10/jessie/Dockerfile

RUN groupadd --gid 1000 node \
  && useradd --uid 1000 --gid node --shell /bin/bash --create-home node


ENV NODE_VERSION 8.11.3

RUN ARCH= && dpkgArch="$(dpkg --print-architecture)" \
  && case "${dpkgArch##*-}" in \
    amd64) ARCH='x64';; \
    ppc64el) ARCH='ppc64le';; \
    s390x) ARCH='s390x';; \
    arm64) ARCH='arm64';; \
    armhf) ARCH='armv7l';; \
    i386) ARCH='x86';; \
    *) echo "unsupported architecture"; exit 1 ;; \
  esac \
  && curl -fsSLO --compressed "https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-linux-$ARCH.tar.xz" \
  && tar -xJf "node-v$NODE_VERSION-linux-$ARCH.tar.xz" -C /usr/local --strip-components=1 --no-same-owner \
  && rm "node-v$NODE_VERSION-linux-$ARCH.tar.xz" \
  && ln -s /usr/local/bin/node /usr/local/bin/nodejs

#ENV YARN_VERSION 1.7.0
#RUN curl -fsSLO --compressed "https://yarnpkg.com/downloads/$YARN_VERSION/yarn-v$YARN_VERSION.tar.gz"
#RUN mkdir -p /opt \
#  && tar -xzf yarn-v$YARN_VERSION.tar.gz -C /opt/ \
#  && ln -s /opt/yarn-v$YARN_VERSION/bin/yarn /usr/local/bin/yarn \
#  && ln -s /opt/yarn-v$YARN_VERSION/bin/yarnpkg /usr/local/bin/yarnpkg \
#  && rm yarn-v$YARN_VERSION.tar.gz
# install mysqli
RUN cd /usr/src && tar xvf php.tar.xz
WORKDIR /usr/src/php-7.1.20/ext/mysqli
RUN phpize && ./configure --with-mysqli=mysqlnd \
  && make && make install
RUN echo "extension=mysqli.so" >> /usr/local/etc/php/conf.d/mysqli.ini


EXPOSE 5121
EXPOSE 5122
ARG NODE_ENV
ENV NODE_ENV $NODE_ENV
WORKDIR $APP_DIR/knight
# vps 单核无法执行 npm i 不在 docker 运行 node
ADD ./api $APP_DIR/knight/api
ADD ./tests $APP_DIR/knight/tests
ADD ./vendor $APP_DIR/knight/vendor
ADD ./public $APP_DIR/knight/public
ADD ./server.js $APP_DIR/knight/
ADD ./package.json $APP_DIR/knight/
RUN npm i --production
# RUN  apt-get install unzip python -y
# RUN composer install --prefer-dist -vvv

EXPOSE 5123
CMD [ "composer", "run-script", "start" ]
