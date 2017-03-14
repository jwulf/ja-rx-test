FROM nginx:1.11

ADD vhost.conf /etc/nginx/conf.d/default.conf
