FROM nginx:1.13.5-alpine
RUN rm -rf /usr/share/nginx/html/*

COPY default.conf /etc/nginx/conf.d/
COPY ./web /usr/share/nginx/html
CMD ["nginx", "-g", "daemon off;"]