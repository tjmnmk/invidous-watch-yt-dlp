It requires a fast internet connection on the server side, as the video is first downloaded there and then streamed.

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
10. **/etc/crontab**: `0 5 * * * www-data find /var/www/html/videos/ -type f -name "*.mp4" -delete`
11. **/etc/crontab**: `23 * * * * root pip3 install --break-system-packages --upgrade yt-dlp`
12. **/etc/php/8.3/apache2/php.ini** - `max_execution_time = 300`

**If YouTube blocks you, you will need to pass the token and cookies to yt-dlp; you can do this by editing `config.php`**

