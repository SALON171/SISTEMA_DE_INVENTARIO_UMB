# SERVIDOR — Instrucciones para ejecutar con XAMPP

1) Instalar XAMPP
- Descargar e instalar desde https://www.apachefriends.org/

2) Carpeta del proyecto
- Copia la carpeta del proyecto a la carpeta `htdocs` de XAMPP.
  Ejemplo Windows:
  C:\xampp\htdocs\SISTEMA_DE_INVENTARIO_UMB

3) Iniciar servicios
- Abre el panel de XAMPP y arranca Apache y MySQL.

4) Acceder al proyecto
- Abre el navegador y entra a:
  http://localhost/SISTEMA_DE_INVENTARIO_UMB/

5) Crear la base de datos (ejemplo)
- Abre phpMyAdmin en: http://localhost/phpmyadmin
- Crea la base de datos `inventario_umb`
- Ejecuta la siguiente consulta SQL para crear la tabla `productos`:

```sql
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  cantidad INT NOT NULL DEFAULT 0,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0.00
);
```

6) Virtual Host (opcional, recomendado)
- Edita: C:\xampp\apache\conf\extra\httpd-vhosts.conf (Windows)
- Agrega un virtual host ejemplo:

```apache
<VirtualHost *:80>
    ServerAdmin admin@local
    DocumentRoot "C:/xampp/htdocs/SISTEMA_DE_INVENTARIO_UMB"
    ServerName inventario.local
    <Directory "C:/xampp/htdocs/SISTEMA_DE_INVENTARIO_UMB">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

- Edita tu archivo hosts (como administrador):
  - Windows: C:\Windows\System32\drivers\etc\hosts
  - Agrega: `127.0.0.1 inventario.local`
- Reinicia Apache y abre: http://inventario.local

7) Notas
- Si usas root sin contraseña (XAMPP por defecto), deja $pass = '' en connect.php.
- Si Apache no arranca, revisa que el puerto 80 no esté en uso (Skype, IIS, etc.)

Si quieres, te puedo dar:
- El SQL completo de inicialización.
- Un paso a paso con capturas (si me dices en qué SO estás: Windows, Linux, Mac).
