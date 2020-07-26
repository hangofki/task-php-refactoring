### Task php refactoring: [Gist](https://gist.github.com/mariusbalcytis/e73370f4d2bda302c7bd867dfeef9751)

---
From project directory:
- Execute script: `php src/app.php input.txt`
- Run tests: `./vendor/bin/phpunit `
- Run php-stan code analyse: `vendor/bin/phpstan analyse -c phpstan.neon.dist`

Also I have additional questions which I would ask you during development:

- Should we do http requests to providers for each transaction? Or we can cache it?
- I didn't validate values from `index.txt`. For example, can `amount` be equal to 0 or less.

I named the providers class too blurry `BinProviderOne`,`CurrencyProviderOne`, I prefer to change it on 
more specific, but can't do that based on their sites name.
