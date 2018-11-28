FROM nginx:1.13.5-alpine
RUN rm -rf /usr/share/nginx/html/*

COPY nginx/default.conf /etc/nginx/conf.d/
COPY src/web/js/ /usr/share/nginx/html/js
COPY src/web/css/ /usr/share/nginx/html/css
COPY src/web/fonts/ /usr/share/nginx/html/fonts
COPY src/web/bundles/ /usr/share/nginx/html/bundles

CMD ["nginx", "-g", "daemon off;"]
