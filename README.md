indexer
=======

Default index listing for NGINX. Simpler **h5ai alternative**.

## Installation
- Clone this repo to wherever you want
- Link the `indexer` to somewhere accessible by your web server

```bash
ln -s /full/path/to/indexer /path/to/www/root/[custom-folder]
```

```
nginx.conf:
    ...
    location /files {
        autoindex on;
        index index.php index.html /indexer/index.php;
        try_files $uri $uri/ =404;
    }
    ...
```
