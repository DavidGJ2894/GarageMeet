# Apache en VPS (Producción)

---

## 1. **Acceso y Clonación del Proyecto desde GitHub**

```bash
# Accede al VPS por SSH
ssh usuario@IP_DE_TU_VPS

# Instalar Git
sudo apt-get update
sudo apt-get install git

# (Opcional) Configurar la clave SSH para GitHub si el repo es privado
ssh-keygen -t ed25519 -C "tu_email@example.com"
cat ~/.ssh/id_ed25519.pub
# Copiar la clave y agrégala en GitHub > Settings > SSH and GPG keys

# Probar la conexión con GitHub
ssh -T git@github.com

# Clonar el repositorio
git clone git@github.com:TU_USUARIO/TU_REPOSITORIO.git
```

---

## 2. **Instala Apache, PHP y Extensiones**

```bash
sudo apt-get update
sudo apt-get install apache2 php libapache2-mod-php php-mysql php-xml php-mbstring php-curl php-zip php-bcmath unzip
```

---

## 3. **Instalar Composer**

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

---

## 4. **Mover el Proyecto a `/var/www` (o clonar directamente en /var/www/)**

```bash
sudo mv ~/GarageMeet-Mechanicals-Service /var/www/GarageMeet-Mechanicals-Service
```

---

## 5. **Instalar Dependencias de Laravel**

```bash
cd /var/www/GarageMeet-Mechanicals-Service
composer install
```

---

## 6. **Configurar Permisos**

```bash
sudo chown -R www-data:www-data /var/www/GarageMeet-Mechanicals-Service
sudo chmod -R 775 storage bootstrap/cache
```

---

## 7. **Configurar el archivo `.env`**

```bash
cp .env.example .env
# Edita las variables en .env (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_KEY, etc.)
nano .env
```

---

## 8. **Genera la clave de aplicación de Laravel**

```bash
php artisan key:generate
```

---

## 9. **Migraciones y Seeders (opcional)**

```bash
php artisan migrate --force
```

---

## 10. **Configura Apache con VirtualHost**

```bash
sudo nano /etc/apache2/sites-available/laravel.conf
```
Pega y edita según tu ruta/dominio:
```apache
<VirtualHost *:80>
    ServerName tu-dominio.com  # O tu IP
    DocumentRoot /var/www/GarageMeet-Mechanicals-Service/public

    <Directory /var/www/GarageMeet-Mechanicals-Service/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/laravel_error.log
    CustomLog ${APACHE_LOG_DIR}/laravel_access.log combined
</VirtualHost>
```
---

## 11. **Activar el sitio y el módulo rewrite**

```bash
sudo a2ensite laravel.conf
sudo a2dissite 000-default.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## 12. **Habilitar HTTPS para tu dominio (Certificado SSL Let's Encrypt)**

### Instala Certbot y módulos SSL:

```bash
sudo apt-get install certbot python3-certbot-apache
sudo a2enmod ssl
```

### Genera el certificado SSL gratis:

```bash
sudo certbot --apache -d tu-dominio.com
```
- Ingresa tu correo cuando lo solicite.
- Acepta los términos de uso.
- Elige redirigir todo el tráfico HTTP a HTTPS si lo deseas.

### VirtualHost para HTTPS generado por Certbot:

Certbot añadirá automáticamente un bloque como este en `/etc/apache2/sites-available/laravel-le-ssl.conf` (o similar):

```apache
<VirtualHost *:443>
    ServerName tu-dominio.com
    DocumentRoot /var/www/GarageMeet-Mechanicals-Service/public

    <Directory /var/www/GarageMeet-Mechanicals-Service/public>
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/tu-dominio.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/tu-dominio.com/privkey.pem

    ErrorLog ${APACHE_LOG_DIR}/laravel_error.log
    CustomLog ${APACHE_LOG_DIR}/laravel_access.log combined
</VirtualHost>
```

---

## 13. **Acceder desde el navegador**

- Ve a `http://tu-dominio.com` y `https://tu-dominio.com` y deberías ver tu app Laravel funcionando (con candado verde 🔒).

---

## 14. **Comandos útiles extra**

```bash
# Ver logs de Apache
tail -f /var/log/apache2/laravel_error.log

# Verificar módulos PHP
php -m

# Reinstalar dependencias
composer install

# Migrar la base de datos
php artisan migrate

# Verifica el estado de los certificados SSL
sudo certbot certificates

# Renovar SSL manualmente
sudo certbot renew
```

---

**¡Listo! Tu app Laravel debería estar corriendo en producción con Apache y HTTPS.**
