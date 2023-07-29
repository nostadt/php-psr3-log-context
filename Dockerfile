FROM composer:2.5.8 AS composer

FROM php:8.1

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt update && apt install jq git zip openssh-server sudo -y
RUN useradd -rm -d /home/ubuntu -s /bin/bash -g root -G sudo -u 1000 test
RUN  echo 'test:test' | chpasswd
RUN service ssh start

EXPOSE 22

CMD ["/usr/sbin/sshd","-D"]

# show that both Composer and PHP run as expected
RUN composer --version && php -v