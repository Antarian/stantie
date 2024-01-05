# Stantie
PHP-based static blog generator

This is a very opinionated static blog generator.
I am using a Twig template system here together with YamlFrontMatter Markdown files for articles.

Templates are read from [templates\pages](templates\pages) directory, data from [data](data) directory and then converted to static html in
[build](build) directory.

Dependencies of the project are intentionally placed to `require-dev` as the project is not meant to run on any production server.
This may affect your IDE to complain about using dependencies outside of tests.

### Install, run and publish
First install dependencies with composer
```shell
composer install
```
then run project to gnerate files
```shell
php public/index.php
```
and generate styles
```shell
npx tailwindcss -i ./public/styles/input.css -o ./build/styles.css --watch
```

After that, you can host site on:
- GitHub pages
- AWS Amplify (CloudFront + S3)
- or any other static website hosting

### License
[MIT License](LICENSE.md)

### Result templates
- /index.html => Blog list page for newest articles
- /about-me.html => Basic info + GitHub, LinkedIn
- /category-xyz.html => Blog filter for category
- /series-xyz.html => Blog filter for series
- /archived.html => Blog filter for archived
- /article/xyz.html => Blog article detail page

### Roadmap
- Tests
- Make it as a static site generator, not only blog
- Add some admin/frontend for editing and saving of MD files and for generation of HTML with click of the button
