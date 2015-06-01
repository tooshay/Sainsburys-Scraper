# Sainbury's Development Test

A tool that scrapes a provided Sainbury's grocery page and returns a JSON array containing titles, Price Per Units, size of the linked product page and a tallied total of all the prices.

# How to run

Get the code and cd in:

```
git clone https://github.com/tooshay/Sainsburys-Scraper.git
cd Sainsbury-Scraper
```

If you haven't got Composer installed, go ahead and install it:

```
$ curl -Ss http://getcomposer.org/installer | php
```

...And fetch all dependencies:

```
$ php composer.phar install
```

Now run the application using the provided URL:

```
app/console/console scrape 'http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&lang Id=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137& categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true'
```

# Unit Tests

Copy the phpunit.xml.dist to phpunit.xml (it's in .gitignore):

```
cp phpunit.xml.dist phpunit.xml
```

...And run the tests:

```
vendor/bin/phpunit -c phpunit.xml
```
