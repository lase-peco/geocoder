# geocoder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lase-peco/geocoder.svg?style=flat-square)](https://packagist.org/packages/lase-peco/geocoder)
[![Total Downloads](https://img.shields.io/packagist/dt/lase-peco/geocoder.svg?style=flat-square)](https://packagist.org/packages/lase-peco/geocoder)

A small geocoder library for laravel.

## Notes

This whole package is depending on the API [nominatim.org](https://nominatim.org/release-docs/develop/).

## Installation

You can install the package via composer:

```bash
composer require lase-peco/gecoder
```

Then publish the config file ```geocoder.php``` with the following command:

``` bash
php artisan vendor:publish --provider="LasePeCo\Geocoder\ServiceProvider" --tag="config"
```
The API from nominatim has a request limit of an absolute maximum of 1 request per second. this is defined in th config file, which you can change at your on risk. you can read about it under [operations.osmfoundation.org/policies](https://operations.osmfoundation.org/policies/nominatim/). 

Also in the config file you can define how long should this library cache the results of your searches. The default is one Month.

## Usage

With the help of the facade ```Geocoder``` you can call two main functions  ```search``` and ```reverse```.

### The search function

You can call the ```search``` function on the  ```Geocoder``` facade and provide it directly with the ```(string)``` address.

this kind of search 'Free-form query' process the provided (string) address first left-to-right and
then right-to-left if that fails. So you may search for ```pilkington avenue, birmingham``` as well as for ```birmingham, pilkington avenue```.

Commas are optional, but improve performance by reducing the complexity of the search.

You can also provide the search function with a part of the address,but the result might not be accurate.
``` php
use LasePeCo\Geocoder\Facades\Geocoder;

Geocoder::search('Rudolf-Diesel-Str. 115, 46485 Wesel') 
``` 

It will return a default json response with the results for the provided address which is accessible as a collection:

``` php
Illuminate\Support\Collection {
  items:[
    0 => {
      "place_id": 317291459
      "licence": "Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright"
      "osm_type": "way"
      "osm_id": 915146453
      "boundingbox": [
            0 => "51.6674904"
            1 => "51.6679331"
            2 => "6.6641183"
            3 => "6.665015"
           ]
      "lat": "51.667710650000004"
      "lon": "6.664615146813532"
      "display_name": "Cubes Wesel, 115, Rudolf-Diesel-Straße, Obrighoven, Obrighoven-Lackhausen, Wesel, Kreis Wesel, Nordrhein-Westfalen, 46485, Deutschland"
      "class": "building"
      "type": "commercial"
      "importance": 0.631
    }
  ]
}
```
Furthermore, you can provide the  ```search``` function with a new object of the ```StructuredAddress``` class, through which you can construct the address:

**This method is preferred to enable the API to find the address better and faster.**

``` php
use LasePeCo\Geocoder\Facades\Geocoder;
use LasePeCo\Geocoder\StructuredAddress;

$address = new StructuredAddress();

$address->street('Rudolf-Diesel-Str. 115')
        ->city('Wesel')
        ->postalcode('46485')
        ->state('Nordrhein-Westfalen')
        ->country('Deutschland');

Geocoder::search($address);
```
***
### The reverse function

With the ```reverse``` function you can reverse search the address with the help of its latitude and longitude: 

``` php
use LasePeCo\Geocoder\Facades\Geocoder;

Geocoder::reverse('lat', 'lon') 
``` 
It will return the same default json response which is accessible as a collection.

***

##Further functions

All the following functions are able to be used with the ```search``` and ```reverse``` functions.

### withAddressDetails()

If you call the ````withAddressDetails()```` on the ```Geocoder``` facade you will get the address detailed:

``` php
use LasePeCo\Geocoder\Facades\Geocoder;

Geocoder::withAddressDetails()->search('Rudolf-Diesel-Str. 115, 46485 Wesel') 

//retrun

"address": {
   "building": "Cubes Wesel"
   "house_number": "115"
   "road": "Rudolf-Diesel-Straße"
   "suburb": "Obrighoven"
   "town": "Wesel"
   "county": "Kreis Wesel"
   "state": "Nordrhein-Westfalen"
   "postcode": "46485"
   "country": "Deutschland"
   "country_code": "de"
  }
``` 
### withExtraTags()
If you call the ````withExtraTags()```` on the ```Geocoder``` facade you will get extra tag for the address:

``` php
use LasePeCo\Geocoder\Facades\Geocoder;

Geocoder::withExtraTags()->search('Rudolf-Diesel-Str. 115, 46485 Wesel') 

//retrun

"extratags": {
  "building:levels": "2"
}
``` 
### language() 

Call the function ```language()``` on the facade ```Geocoder``` with the language as a parameter to translate the result of the search.

``` php 
use LasePeCo\Geocoder\Facades\Geocoder;

Geocoder::withAddressDetails()->withExtraTags()->language('en')->search('Rudolf-Diesel-Str. 115, 46485 Wesel');

//return

Illuminate\Support\Collection {
  #items: [
    0 => {
      "place_id": 317291459
      "licence": "Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright"
      "osm_type": "way"
      "osm_id": 915146453
      "boundingbox":[
        0 => "51.6674904"
        1 => "51.6679331"
        2 => "6.6641183"
        3 => "6.665015"
      ]
      "lat": "51.667710650000004"
      "lon": "6.664615146813532"
      "display_name": "Cubes Wesel, 115, Rudolf-Diesel-Straße, Obrighoven, Obrighoven-Lackhausen, Wesel, Kreis Wesel, North Rhine-Westphalia, 46485, Germany"
      "class": "building"
      "type": "commercial"
      "importance": 0.631
      "address": {#1853 ▼
        "building": "Cubes Wesel"
        "house_number": "115"
        "road": "Rudolf-Diesel-Straße"
        "suburb": "Obrighoven"
        "town": "Wesel"
        "county": "Kreis Wesel"
        "state": "North Rhine-Westphalia"
        "postcode": "46485"
        "country": "Germany"
        "country_code": "de"
      }
      "extratags": {#1861 ▼
        "building:levels": "2"
      }
    }
  ]
}
```
### withNameDetails() 

Call the function ```withNameDetails()``` on the facade ```Geocoder``` with the language as a parameter to translate the result of the search.

``` php 
use LasePeCo\Geocoder\Facades\Geocoder;

Geocoder::withNameDetails()->search('Rudolf-Diesel-Str. 115, 46485 Wesel');

//return

"namedetails": {
  "name": "Cubes Wesel"
}
```

### format() 

This package supports the following formats: ```xml```,```json```,```jsonv2```,```geojson```,```geocodejson```.

Call the function ```format()``` on the facade ```Geocoder``` with the desired format as a parameter.

For the formats ```xml```and ```json``` there is two functions that you can call ```xml()``` and ```json()```.

``` php 
use LasePeCo\Geocoder\Facades\Geocoder;

Geocoder::format('xml')->search('Rudolf-Diesel-Str. 115, 46485 Wesel')
//or
Geocoder::xml()->search('Rudolf-Diesel-Str. 115, 46485 Wesel')

//return

<?xml version="1.0" encoding="UTF-8" ?>
<searchresults timestamp='Fri, 15 Oct 21 07:55:41 +0000' attribution='Data © OpenStreetMap contributors, ODbL 1.0. http://www.openstreetmap.org/copyright' querystring='Rudolf-Diesel-Str. 115, 46485 Wesel' exclude_place_ids='317291459' more_url='https://nominatim.openstreetmap.org/search/?q=Rudolf-Diesel-Str.+115%2C+46485+Wesel&amp;exclude_place_ids=317291459&amp;format=xml'> 
    <place place_id='317291459' osm_type='way' osm_id='915146453' place_rank='30' address_rank='30' boundingbox="51.6674904,51.6679331,6.6641183,6.665015" lat='51.667710650000004' lon='6.664615146813532' display_name='Cubes Wesel, 115, Rudolf-Diesel-Straße, Obrighoven, Obrighoven-Lackhausen, Wesel, Kreis Wesel, Nordrhein-Westfalen, 46485, Deutschland' class='building' type='commercial' importance='0.631'/>
</searchresults> 

```



### Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email a.dabak@lase-peco.com instead of using the issue tracker.

## Credits

- [Ahmed Dabak](https://github.com/lase-peco)
- [Abdulsalam Emesh](https://github.com/lase-peco)
- [All Contributors](CONTRIBUTING.md)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
