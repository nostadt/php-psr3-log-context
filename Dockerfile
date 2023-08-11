FROM composer:2.5.8 AS composer

FROM php:8.1

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install various packages
RUN apt-get update

RUN apt update && apt install jq git zip openssh-server sudo -y
RUN useradd -rm -d /home/ubuntu -s /bin/bash -g root -G sudo -u 1000 test
RUN  echo 'test:test' | chpasswd
RUN service ssh start

EXPOSE 22

CMD ["/usr/sbin/sshd","-D"]

## Install for phive.io
RUN apt-get update && apt-get install -y gnupg
RUN apt-get install -y ca-certificates wget

RUN wget -O phive.phar https://phar.io/releases/phive.phar
RUN wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc
RUN gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79
RUN gpg --verify phive.phar.asc phive.phar
RUN chmod +x phive.phar
RUN mv phive.phar /usr/local/bin/phive

# show that both Composer and PHP run as expected
RUN composer --version && php -v