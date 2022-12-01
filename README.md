# sitemap

## Commands

```shell
vendor/bin/sitemap generate
```

```shell
vendor/bin/sitemap status
```

## Example yaml configuration

```yaml
enjoyscms/sitemap:
    baseUrl: &baseUrl "http://localhost"
    filename: /sitemap.xml
    stylesheet:
        - *baseUrl
        - /stylesheet.xsl
    collectors:
        - \EnjoysCMS\Module\Sitemap\ExampleCollector
```

## All parameters of configuration
```yaml
baseUrl: 'set baseUrl in /config.yml'
filename: /sitemap.xml
maxUrls: 50000 #Maximum allowed number of URLs in a single file.
useGzip: false #Whether to gzip the resulting files or not
useIndent: true #if XML should be indented
maxBytes: 10485760 #Maximum allowed number of bytes in a single file. default: 10485760
bufferSize: 10 #Number of URLs to be kept in memory before writing it to file
stylesheet: null
collectors: []
```
