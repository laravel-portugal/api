FROM reg.laravel.pt/frontend_base:1

ADD . /var/www/html

EXPOSE 80
CMD php -S 0.0.0.0:80 -t ./public
