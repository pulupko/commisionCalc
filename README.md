# commisionCalc

## Description

This code aims to compute commissions for transactions that have already taken place. The transactions are provided in JSON format, with each transaction on a separate line in the input file. The BIN number (i.e. the first digits of a credit card number) can be used to determine the country where the card was issued. Commission rates vary depending on whether the card was issued in the EU or outside the EU. Finally, all commissions are calculated in EUR currency.

## Installation

To install this project, run the following command:
```
composer install
```

## Usage

To use this project, simply provide a JSON file containing the past transactions, with each transaction on a separate line. The commission calculator will read the file and output the calculated commissions.
To run script use this command:
```
php app.php input.txt   
```

## Testing

To run tests for this project, use the following command from the root directory:
```
vendor/bin/phpunit
```
