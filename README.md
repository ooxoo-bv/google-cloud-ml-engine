# Google Cloud ML Engine Client Library for PHP

**Please help to improve this library until Google provides an official php library.**

### Installation

**NOTE** This package is not intended for direct use. It provides common infrastructure
to the rest of the Google Cloud PHP components.

```sh
$ composer require ooxoo/google-cloud-ml-engine
```

### Authentication

Please see the [Authentication guide](https://github.com/googleapis/google-cloud-php/blob/master/AUTHENTICATION.md) for more information
on authenticating your client. Once authenticated, you'll be ready to start making requests.

### Sample

```php
require 'vendor/autoload.php';

use Ooxoo\GCloud\MLEngine\MLEngineClient;

$client = new MLEngineClient();

$bucket = $storage->bucket('my_bucket');

// Upload a file to the bucket.
$client->predict(
    'ml_engine_model',
    [
        [
            {
              "label": "beach",
              "scores": [0.1, 0.9]
            },
            {
              "label": "car",
              "scores": [0.75, 0.25]
            }
        ]       
    ]
));
```

### Next Steps

1. Understand the [official documentation](https://cloud.google.com/ml-engine/docs/).
