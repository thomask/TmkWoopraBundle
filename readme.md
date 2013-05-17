<!-- # PHP A/B Bundle

Bundle for using [PHP AB](http://phpabtest.com/) with Symfony2

## Installation

### Add to composer.json
    "require": {
        ...
        "thomask/php-ab-bundle": "*"
    }

### Register the bundle
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Tmk\PhpAbBundle\TmkPhpAbBundle(),
        );
    }

## Usage

### In templates
    {% set testName = "signup-link-text" %}
    {% set testGroup = ab(testName, ["signup-now", "direct-signup"]) %}

    {% if testGroup == 'signup-now' %}
        <a href="/signup">Signup Now</a>
    {% elseif testGroup == 'direct-signup' %}
        <a href="/signup">Direct Signup</a>
    {% testGroup == 'control' %}
        <a href="/signup">Signup</a>
    {% endif %}


### Setup your Google Analytics tracking code.
Check out the [PHP AB documentation](http://phpabtest.com/documentation#advanced-ga) to see how.


 -->
