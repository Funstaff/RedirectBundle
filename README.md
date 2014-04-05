RedirectBundle
==============

Master: [![Build Status](https://travis-ci.org/Funstaff/RedirectBundle.svg?branch=master)](https://travis-ci.org/Funstaff/RedirectBundle)

This is a redirect system for symfony2.

This bundle use the framework css [getuikit](http://getuikit.com/ "getuikit")

### Enable the bundle
Enable the bundle in the kernel:

```
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Funstaff\Bundle\RedirectBundle\FunstaffRedirectBundle(),
    );
}
```

### Create table redirect in database
``` bash
$ php app/console doctrine:schema:update --force
```

### app/config/routing.yml
``` yaml
funstaff_redirect:
    resource: "@FunstaffRedirectBundle/Resources/config/routing.xml"
```

### Configuration
Default configuration:

``` yaml
funstaff_redirect:
    listener:       exception
    layout:         '::base.html.twig'
    enabled_stat:   true
    export_path:    %kernel.root_dir%/export
```

If you would like to redirect on request, change the listener parameter to "request".
