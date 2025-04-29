```bash
sudo apt update
sudo apt install apache2 php libapache2-mod-php8.3
a2enmod rewrite
a2enmod php
systemctl restart apache2
pip3 install --break-system-packages --upgrade yt-dlp
```

1. `nginx_example_vshost_invidous_and_apache.conf`
2. `apache2_vhost_example.conf`
3. copy `watch.php and .htaccess` to the `/var/www/html/`
4. `mkdir /var/www/html/videos`
5. `chown www-data: /var/www/html/videos`
6. `mkdir /var/www/.cache` # for yt-dlp cache
7. `chown www-data /var/www/.cache`
8. `systemctl restart nginx`
9. `systemctl restart apache2`
10. **/etc/crontab**: `0 5 * * * root find /var/www/html/videos/ -type f -name "*.mp4" -delete`
