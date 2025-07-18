# Usa una imagen oficial de PHP
FROM php:8.2-cli

# Establece el directorio de trabajo
WORKDIR /app

# Copia todos tus archivos al contenedor
COPY . .

# Render asigna un puerto en la variable $PORT
ENV PORT=10000

# Comando para iniciar el servidor embebido de PHP
CMD php -S 0.0.0.0:$PORT -t .
