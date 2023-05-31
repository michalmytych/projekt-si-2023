# Projekt SI

```bash
./vendor/bin/php-cs-fixer fix src/ --rules=@Symfony,@PSR1,@PSR2,@PSR12
vendor/bin/phpcs --standard=Symfony src/
vendor/bin/phpcbf --standard=Symfony src/ --ignore=Kernel.php
```