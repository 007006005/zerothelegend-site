FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql \
    && for module in mpm_event mpm_worker mpm_prefork; do \
        a2dismod "${module}" >/dev/null 2>&1 || true; \
        rm -f "/etc/apache2/mods-enabled/${module}.load" "/etc/apache2/mods-enabled/${module}.conf"; \
    done \
    && a2enmod mpm_prefork rewrite headers \
    && test "$(apache2ctl -M 2>/dev/null | grep -Ec 'mpm_(event|worker|prefork)_module')" -eq 1

COPY public/ /var/www/html/

EXPOSE 8080

CMD ["bash", "-lc", "for module in mpm_event mpm_worker mpm_prefork; do a2dismod \"$module\" >/dev/null 2>&1 || true; rm -f \"/etc/apache2/mods-enabled/${module}.load\" \"/etc/apache2/mods-enabled/${module}.conf\"; done; a2enmod mpm_prefork >/dev/null; test \"$(apache2ctl -M 2>/dev/null | grep -Ec 'mpm_(event|worker|prefork)_module')\" -eq 1 || { echo 'Apache must have exactly one MPM enabled' >&2; exit 1; }; port=${PORT:-8080}; sed -ri \"s/Listen 80/Listen ${port}/\" /etc/apache2/ports.conf; sed -ri \"s/<VirtualHost \\*:80>/<VirtualHost *:${port}>/\" /etc/apache2/sites-available/000-default.conf; exec apache2-foreground"]
