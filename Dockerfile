# Default Dockerfile
#
FROM hyperf/hyperf:8.0-alpine-v3.12-swoole
LABEL maintainer="zfeig <fzhang@suntekcorps.com>" version="1.0" license="MIT" app.name="php-console-v2"

##
# ---------- env settings ----------
##
# --build-arg timezone=Asia/Shanghai
ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    APP_ENV=prod \
    SCAN_CACHEABLE=(true) 

# update
RUN set -ex \   
    # show php version and extensions
    && apk add php-mongodb \
    && apk add php-redis\
    && php -v \
    && php -m \
    && php --ri swoole \
    #  ---------- some config ----------
    && cd /etc/php* \
    # - config PHP
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"



WORKDIR /opt/www

#构建镜像时，请根据时间项目路径填写
VOLUME [ "/disks/F/php-console-v2"]

RUN chmod -R 0777 /opt/www 
# Composer Cache
# COPY ./composer.* /opt/www/
# RUN composer install --no-dev --no-scripts

COPY . /opt/www
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer

RUN composer install --no-dev -o 

EXPOSE 9001

ENTRYPOINT ["php", "/opt/www/index.php"]