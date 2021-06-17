<p align="center">
	<a href="https://www.wp-pay.org/">
		<img src="https://www.wp-pay.org/assets/pronamic-pay.svgo-min.svg" alt="WordPress Pay » Gateway » PayPal" width="72" height="72">
	</a>
</p>

<h1 align="center">WordPress Pay » Gateway » PayPal</h3>

<p align="center">
	PayPal driver for the WordPress payment processing library.
</p>

[![Build Status](https://travis-ci.org/wp-pay-gateways/paypal.svg?branch=develop)](https://travis-ci.org/wp-pay-gateways/paypal)
[![Coverage Status](https://coveralls.io/repos/wp-pay-gateways/paypal/badge.svg?branch=develop&service=github)](https://coveralls.io/github/wp-pay-gateways/paypal?branch=develop)
[![Latest Stable Version](https://img.shields.io/packagist/v/wp-pay-gateways/paypal.svg)](https://packagist.org/packages/wp-pay-gateways/paypal)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/wp-pay-gateways/paypal.svg)](https://packagist.org/packages/wp-pay-gateways/paypal)
[![Total Downloads](https://img.shields.io/packagist/dt/wp-pay-gateways/paypal.svg)](https://packagist.org/packages/wp-pay-gateways/paypal)
[![Packagist Pre Release](https://img.shields.io/packagist/vpre/wp-pay-gateways/paypal.svg)](https://packagist.org/packages/wp-pay-gateways/paypal)
[![License](https://img.shields.io/packagist/l/wp-pay-gateways/paypal.svg)](https://packagist.org/packages/wp-pay-gateways/paypal)
[![Built with Grunt](https://gruntjs.com/cdn/builtwith.svg)](http://gruntjs.com/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wp-pay-gateways/paypal/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/wp-pay-gateways/paypal/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/wp-pay-gateways/paypal/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/wp-pay-gateways/paypal/?branch=develop)
[![Build Status](https://scrutinizer-ci.com/g/wp-pay-gateways/paypal/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/wp-pay-gateways/paypal/build-status/develop)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/wp-pay-gateways/paypal/badges/code-intelligence.svg?b=develop)](https://scrutinizer-ci.com/code-intelligence)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fwp-pay-gateways%2Fpaypal.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fwp-pay-gateways%2Fpaypal?ref=badge_shield)

## Table of contents

- [WordPress Filters](#wordpress-filters)
- [Simulate Requests](#simulate-requests)
- [Links](#links)

## WordPress Filters

View [docs/hooks.md](docs/hooks.md) for the available WordPres filters in this library.

## Simulate Requests

### Report

```
curl --request POST "https://example.com/wp-json/pronamic-pay/paypal/v1/ipn-listener" \
	--user-agent "PayPal IPN ( https://www.paypal.com/ipn )" \
	--data-raw "mc_gross=1401.00&protection_eligibility=Eligible&address_status=confirmed&item_number1=&payer_id=F9S93N7GNEL7W&address_street=25513540+River+N343+W&payment_date=05%3A01%3A46+Jun+17%2C+2021+PDT&payment_status=Pending&charset=windows-1252&address_zip=GJ&first_name=test&address_country_code=NL&address_name=test+buyer&notify_version=3.9&custom=&payer_status=verified&business=info-facilitator%40remcotolsma.nl&address_country=Netherlands&num_cart_items=1&address_city=Den+Haag&verify_sign=Ai2wvIVeSssqwxVcMK9XcafWsgAPA6uNE6nnbvDILmqDs0csJU91zp3n&payer_email=info-buyer%40remcotolsma.nl&txn_id=6GJ83385S3320270B&payment_type=instant&last_name=buyer&item_name1=Payment+416&address_state=2585&receiver_email=info-facilitator%40remcotolsma.nl&shipping_discount=0.00&quantity1=1&insurance_amount=0.00&receiver_id=G52WMAV7T2F8E&pending_reason=multi_currency&txn_type=cart&discount=0.00&mc_gross_1=1401.00&mc_currency=EUR&residence_country=NL&test_ipn=1&shipping_method=Default&transaction_subject=&payment_gross=&ipn_track_id=292d19dc6ff2e"
```

## Links

- https://github.com/DigiWallet/transaction-sdk
- https://webhook.site/
- https://hookbin.com/
- https://requestbin.net/
